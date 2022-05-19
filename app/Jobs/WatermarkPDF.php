<?php

namespace App\Jobs;

use App\Resource;
use App\ResourceAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class WatermarkPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ResourceAttachment
     */
    private ResourceAttachment $attachment;
    /**
     * @var Resource
     */
    private $resource;
    private $temp_file;

    /**
     * Create a new job instance.
     *
     * @param ResourceAttachment $attachment
     * @param                    $temp_file
     * @param Resource           $resource
     */
    public function __construct(
        ResourceAttachment $attachment,
        $temp_file,
        Resource $resource
    )
    {
        $this->attachment = $attachment;
        $this->temp_file = $temp_file;
        $this->resource = $resource;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $temp_file = $this->temp_file;
        $attachment = $this->attachment;

        $version = get_pdf_version_and_pages($temp_file);

        if ($version == 0) {
            // Something's wrong – pdfinfo wasn't able to find
            // the PDF version of this file . We won't bother
            // watermarking this file anymore, so we set the
            // field as true and return the original file.
            $attachment->file_watermarked = true;
            $attachment->save();
            return;
        } elseif ($version > 1.4) {
            $temp_file = lower_pdf_version(
                $temp_file,
                $attachment->file_name
            );
        }

        $logo = Storage::disk('s3')->get(
            'public/img/watermark.png'
        );
        $temp_logo = tempnam(sys_get_temp_dir(), 'watermark_');
        file_put_contents($temp_logo, $logo);

        list(
            $license_button_1, $license_button_2
            )
            = get_license_buttons(
            $this->resource
        );

        $new_file = watermark_pdf(
            $temp_file,
            $temp_logo,
            $license_button_1,
            $license_button_2
        );

        Storage::disk('s3')->put(
            'resources/' . $attachment->file_name,
            $new_file
        );

        $attachment->file_watermarked = true;
        $attachment->save();
    }
}
