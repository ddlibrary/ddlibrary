<?php

namespace App\Http\Controllers;

use App\Models\DdlFile;
use App\Models\News;
use App\Models\Page;
use App\Models\Resource;
use App\Models\ResourceAttachment;
use App\Models\ResourceAuthor;
use App\Models\ResourceCopyrightHolder;
use App\Models\ResourceCreativeCommon;
use App\Models\ResourceEducationalResource;
use App\Models\ResourceEducationalUse;
use App\Models\ResourceIamAuthor;
use App\Models\ResourceKeyword;
use App\Models\ResourceLearningResourceType;
use App\Models\ResourceLevel;
use App\Models\ResourcePublisher;
use App\Models\ResourceSharePermission;
use App\Models\ResourceSubjectArea;
use App\Models\ResourceTranslationRight;
use App\Models\ResourceTranslator;
use App\Models\TaxonomyHierarchy;
use App\Models\TaxonomyTerm;
use App\Models\TaxonomyVocabulary;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SyncController extends Controller
{
    public function index(): View
    {
        $this->middleware('admin');

        $rmResource = new Resource();
        $rmDdlFile = new DdlFile();
        $rmNews = new News();
        $rmPage = new Page();
        $rmTaxonomyTerm = new TaxonomyTerm();

        $rmResourceRecords = $rmResource->setConnection('mysql_remote')->newQuery()->get();
        $rmDdlFileRecords = $rmDdlFile->setConnection('mysql_remote')->newQuery()->get();
        $rmNewsRecords = $rmNews->setConnection('mysql_remote')->newQuery()->get();
        $rmPageRecords = $rmPage->setConnection('mysql_remote')->newQuery()->get();
        $rmTaxonomyTermRecords = $rmTaxonomyTerm->setConnection('mysql_remote')->newQuery()->get();

        $Resource = new Resource();
        $DdlFile = new DdlFile();
        $News = new News();
        $Page = new Page();
        $ResourceLevel = new ResourceLevel();
        $TaxonomyTerm = new TaxonomyTerm();

        $ResourceRecords = $Resource->all();
        $DdlFileRecords = $DdlFile->all();
        $NewsRecords = $News->all();
        $PageRecords = $Page->all();
        $TaxonomyTermRecords = $TaxonomyTerm->all();

        $diffResourceRecords = $rmResourceRecords->diffAssoc($ResourceRecords);
        $diffDdlFileRecords = $rmDdlFileRecords->diffAssoc($DdlFileRecords);
        $diffNewsRecords = $rmNewsRecords->diffAssoc($NewsRecords);
        $diffPageRecords = $rmPageRecords->diffAssoc($PageRecords);
        $diffTaxonomyTermRecords = $rmTaxonomyTermRecords->diffAssoc($TaxonomyTermRecords);

        $countResourceRecords = count($diffResourceRecords);
        $countDdlFileRecords = count($diffDdlFileRecords);
        $countNewsRecords = count($diffNewsRecords);
        $countPageRecords = count($diffPageRecords);
        $countTaxonomyTermRecords = count($diffTaxonomyTermRecords);

        return view('admin.sync.sync_status', compact(
            'countResourceRecords',
            'countDdlFileRecords',
            'countNewsRecords',
            'countPageRecords',
            'countTaxonomyTermRecords'
        ));
    }

    public function SyncIt(): RedirectResponse
    {
        $this->middleware('admin');

        $rmResource = new Resource();
        $rmDdlFile = new DdlFile();
        $rmNews = new News();
        $rmPage = new Page();
        $rmResourceLevel = new ResourceLevel();
        $rmResourceAttachment = new ResourceAttachment();
        $rmResourceSubjectArea = new ResourceSubjectArea();
        $rmTaxonomyTerm = new TaxonomyTerm();
        $rmTaxonomyVocabulary = new TaxonomyVocabulary();
        $rmTaxonomyHierarchy = new TaxonomyHierarchy();
        $rmResourceKeyword = new ResourceKeyword();
        $rmResourceLearningResourceType = new ResourceLearningResourceType();
        $rmResourceEducationalUse = new ResourceEducationalUse();
        $rmResourceTranslationRight = new ResourceTranslationRight();
        $rmResourceEducationalResource = new ResourceEducationalResource();
        $rmResourceCopyrightHolder = new ResourceCopyrightHolder();
        $rmResourceCreativeCommon = new ResourceCreativeCommon();
        $rmResourceSharePermission = new ResourceSharePermission();
        $rmResourceAuthor = new ResourceAuthor();
        $rmResourcePublisher = new ResourcePublisher();
        $rmResourceTranslator = new ResourceTranslator();
        $rmResourceIamAuthor = new ResourceIamAuthor();

        $rmResourceRecords = $rmResource->setConnection('mysql_remote')->newQuery()->get();
        $rmDdlFileRecords = $rmDdlFile->setConnection('mysql_remote')->newQuery()->get();
        $rmNewsRecords = $rmNews->setConnection('mysql_remote')->newQuery()->get();
        $rmPageRecords = $rmPage->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceLevelRecords = $rmResourceLevel->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceAttachmentRecords = $rmResourceAttachment->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceSubjectAreaRecords = $rmResourceSubjectArea->setConnection('mysql_remote')->newQuery()->get();
        $rmTaxonomyTermRecords = $rmTaxonomyTerm->setConnection('mysql_remote')->newQuery()->get();
        $rmTaxonomyVocabularyRecords = $rmTaxonomyVocabulary->setConnection('mysql_remote')->newQuery()->get();
        $rmTaxonomyHierarchyRecords = $rmTaxonomyHierarchy->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceKeywordRecords = $rmResourceKeyword->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceLearningResourceTypeRecords = $rmResourceLearningResourceType->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceEducationalUseRecords = $rmResourceEducationalUse->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceTranslationRightRecords = $rmResourceTranslationRight->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceEducationalResourceRecords = $rmResourceEducationalResource->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceCopyrightHolderRecords = $rmResourceCopyrightHolder->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceCreativeCommonRecords = $rmResourceCreativeCommon->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceSharePermissionRecords = $rmResourceSharePermission->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceAuthorRecords = $rmResourceAuthor->setConnection('mysql_remote')->newQuery()->get();
        $rmResourcePublisherRecords = $rmResourcePublisher->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceTranslatorRecords = $rmResourceTranslator->setConnection('mysql_remote')->newQuery()->get();
        $rmResourceIamAuthorRecords = $rmResourceIamAuthor->setConnection('mysql_remote')->newQuery()->get();

        $Resource = new Resource();
        $DdlFile = new DdlFile();
        $News = new News();
        $Page = new Page();
        $ResourceLevel = new ResourceLevel();
        $ResourceAttachment = new ResourceAttachment();
        $ResourceSubjectArea = new ResourceSubjectArea();
        $TaxonomyTerm = new TaxonomyTerm();
        $TaxonomyVocabulary = new TaxonomyVocabulary();
        $TaxonomyHierarchy = new TaxonomyHierarchy();
        $ResourceKeyword = new ResourceKeyword();
        $ResourceLearningResourceType = new ResourceLearningResourceType();
        $ResourceEducationalUse = new ResourceEducationalUse();
        $ResourceTranslationRight = new ResourceTranslationRight();
        $ResourceEducationalResource = new ResourceEducationalResource();
        $ResourceCopyrightHolder = new ResourceCopyrightHolder();
        $ResourceCreativeCommon = new ResourceCreativeCommon();
        $ResourceSharePermission = new ResourceSharePermission();
        $ResourceAuthor = new ResourceAuthor();
        $ResourcePublisher = new ResourcePublisher();
        $ResourceTranslator = new ResourceTranslator();
        $ResourceIamAuthor = new ResourceIamAuthor();

        $ResourceRecords = $Resource->all();
        $DdlFileRecords = $DdlFile->all();
        $NewsRecords = $News->all();
        $PageRecords = $Page->all();
        $ResourceLevelRecords = $ResourceLevel->all();
        $ResourceAttachmentRecords = $ResourceAttachment->all();
        $ResourceSubjectAreaRecords = $ResourceSubjectArea->all();
        $TaxonomyTermRecords = $TaxonomyTerm->all();
        $TaxonomyVocabularyRecords = $TaxonomyVocabulary->all();
        $TaxonomyHierarchyRecords = $TaxonomyHierarchy->all();
        $TaxonomyTermRecords = $TaxonomyTerm->all();
        $ResourceKeywordRecords = $ResourceKeyword->all();
        $ResourceLearningResourceTypeRecords = $ResourceLearningResourceType->all();
        $ResourceEducationalUseRecords = $ResourceEducationalUse->all();
        $ResourceTranslationRightRecords = $ResourceTranslationRight->all();
        $ResourceEducationalResourceRecords = $ResourceEducationalResource->all();
        $ResourceCopyrightHolderRecords = $ResourceCopyrightHolder->all();
        $ResourceCreativeCommonRecords = $ResourceCreativeCommon->all();
        $ResourceSharePermissionRecords = $ResourceSharePermission->all();
        $ResourceAuthorRecords = $ResourceAuthor->all();
        $ResourcePublisherRecords = $ResourcePublisher->all();
        $ResourceTranslatorRecords = $ResourceTranslator->all();
        $ResourceIamAuthorRecords = $ResourceIamAuthor->all();

        $diffResourceRecords = $rmResourceRecords->diffAssoc($ResourceRecords);
        $diffDdlFileRecords = $rmDdlFileRecords->diffAssoc($DdlFileRecords);
        $diffNewsRecords = $rmNewsRecords->diffAssoc($NewsRecords);
        $diffPageRecords = $rmPageRecords->diffAssoc($PageRecords);
        $diffResourceLevelRecords = $rmResourceLevelRecords->diffAssoc($ResourceLevelRecords);
        $diffResourceAttachmentRecords = $rmResourceAttachmentRecords->diffAssoc($ResourceAttachmentRecords);
        $diffResourceSubjectAreaRecords = $rmResourceSubjectAreaRecords->diffAssoc($ResourceSubjectAreaRecords);
        $diffTaxonomyTermRecords = $rmTaxonomyTermRecords->diffAssoc($TaxonomyTermRecords);
        $diffTaxonomyVocabularyRecords = $rmTaxonomyVocabularyRecords->diffAssoc($TaxonomyVocabularyRecords);
        $diffTaxonomyHierarchyRecords = $rmTaxonomyHierarchyRecords->diffAssoc($TaxonomyHierarchyRecords);
        $diffResourceKeywordRecords = $rmResourceKeywordRecords->diffAssoc($ResourceKeywordRecords);
        $diffResourceLearningResourceTypeRecords = $rmResourceLearningResourceTypeRecords->diffAssoc($ResourceLearningResourceTypeRecords);
        $diffResourceEducationalUseRecords = $rmResourceEducationalUseRecords->diffAssoc($ResourceEducationalUseRecords);
        $diffResourceTranslationRightRecords = $rmResourceTranslationRightRecords->diffAssoc($ResourceTranslationRightRecords);
        $diffResourceEducationalResourceRecords = $rmResourceEducationalResourceRecords->diffAssoc($ResourceEducationalResourceRecords);
        $diffResourceCopyrightHolderRecords = $rmResourceCopyrightHolderRecords->diffAssoc($ResourceCopyrightHolderRecords);
        $diffResourceCreativeCommonRecords = $rmResourceCreativeCommonRecords->diffAssoc($ResourceCreativeCommonRecords);
        $diffResourceSharePermissionRecords = $rmResourceSharePermissionRecords->diffAssoc($ResourceSharePermissionRecords);
        $diffResourceAuthorRecords = $rmResourceAuthorRecords->diffAssoc($ResourceAuthorRecords);
        $diffResourcePublisherRecords = $rmResourcePublisherRecords->diffAssoc($ResourcePublisherRecords);
        $diffResourceTranslatorRecords = $rmResourceTranslatorRecords->diffAssoc($ResourceTranslatorRecords);
        $diffResourceIamAuthorRecords = $rmResourceIamAuthorRecords->diffAssoc($ResourceIamAuthorRecords);

        //Resources
        foreach ($diffResourceRecords as $ResourceRecord) {
            $newInstance = Resource::findOrNew($ResourceRecord->id);
            $newInstance->title = $ResourceRecord->title;
            $newInstance->abstract = $ResourceRecord->abstract;
            $newInstance->language = $ResourceRecord->language;
            $newInstance->user_id = $ResourceRecord->user_id;
            $newInstance->status = $ResourceRecord->status;
            $newInstance->tnid = $ResourceRecord->tnid;
            $newInstance->created_at = $ResourceRecord->created_at;
            $newInstance->updated_at = $ResourceRecord->updated_at;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Images
        foreach ($diffDdlFileRecords as $DdlFileRecord) {
            $newInstance = DdlFile::findOrNew($DdlFileRecord->id);
            $newInstance->name = $DdlFileRecord->name;
            $newInstance->url = $DdlFileRecord->url;
            $newInstance->size = $DdlFileRecord->size;
            $newInstance->type = $DdlFileRecord->type;
            $newInstance->created_at = $DdlFileRecord->created_at;
            $newInstance->updated = $DdlFileRecord->updated;
            $newInstance->updated_at = $DdlFileRecord->updated_at;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //News
        foreach ($diffNewsRecords as $NewsRecord) {
            $newInstance = News::findOrNew($NewsRecord->id);
            $newInstance->title = $NewsRecord->title;
            $newInstance->summary = $NewsRecord->summary;
            $newInstance->body = $NewsRecord->body;
            $newInstance->language = $NewsRecord->language;
            $newInstance->user_id = $NewsRecord->user_id;
            $newInstance->tnid = $NewsRecord->tnid;
            $newInstance->status = $NewsRecord->status;
            $newInstance->created_at = $NewsRecord->created_at;
            $newInstance->updated_at = $NewsRecord->updated_at;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Pages
        foreach ($diffPageRecords as $PageRecord) {
            $newInstance = News::findOrNew($PageRecord->id);
            $newInstance->title = $PageRecord->title;
            $newInstance->summary = $PageRecord->summary;
            $newInstance->body = $PageRecord->body;
            $newInstance->language = $PageRecord->language;
            $newInstance->user_id = $PageRecord->user_id;
            $newInstance->tnid = $PageRecord->tnid;
            $newInstance->status = $PageRecord->status;
            $newInstance->created_at = $PageRecord->created_at;
            $newInstance->updated_at = $PageRecord->updated_at;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Levels
        foreach ($diffResourceLevelRecords as $ResourceLevelRecord) {
            $newInstance = ResourceLevel::findOrNew($ResourceLevelRecord->id);
            $newInstance->resource_id = $ResourceLevelRecord->resource_id;
            $newInstance->tid = $ResourceLevelRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Attachments
        foreach ($diffResourceAttachmentRecords as $ResourceAttachmentRecord) {
            $newInstance = ResourceAttachment::findOrNew($ResourceAttachmentRecord->id);
            $newInstance->resource_id = $ResourceAttachmentRecord->resource_id;
            $newInstance->file_name = $ResourceAttachmentRecord->file_name;
            $newInstance->file_mime = $ResourceAttachmentRecord->file_mime;
            $newInstance->file_size = $ResourceAttachmentRecord->file_size;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Subject Areas
        foreach ($diffResourceSubjectAreaRecords as $ResourceSubjectAreaRecord) {
            $newInstance = ResourceSubjectArea::findOrNew($ResourceSubjectAreaRecord->id);
            $newInstance->resource_id = $ResourceSubjectAreaRecord->resource_id;
            $newInstance->tid = $ResourceSubjectAreaRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Taxonomy Terms
        foreach ($diffTaxonomyTermRecords as $TaxonomyTermRecord) {
            $newInstance = TaxonomyTerm::findOrNew($TaxonomyTermRecord->id);
            $newInstance->vid = $TaxonomyTermRecord->vid;
            $newInstance->name = $TaxonomyTermRecord->name;
            $newInstance->weight = $TaxonomyTermRecord->weight;
            $newInstance->language = $TaxonomyTermRecord->language;
            $newInstance->tnid = $TaxonomyTermRecord->tnid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Taxonomy Vocabulary
        foreach ($diffTaxonomyVocabularyRecords as $TaxonomyVocabularyRecord) {
            $newInstance = TaxonomyVocabulary::findOrNew($TaxonomyVocabularyRecord->id);
            $newInstance->name = $TaxonomyVocabularyRecord->name;
            $newInstance->weight = $TaxonomyVocabularyRecord->weight;
            $newInstance->language = $TaxonomyVocabularyRecord->language;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Taxonomy Hierarchy
        foreach ($diffTaxonomyHierarchyRecords as $TaxonomyHierarchyRecord) {
            $newInstance = TaxonomyHierarchy::findOrNew($TaxonomyHierarchyRecord->id);
            $newInstance->tid = $TaxonomyHierarchyRecord->tid;
            $newInstance->parent = $TaxonomyHierarchyRecord->parent;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Keywords
        foreach ($diffResourceKeywordRecords as $ResourceKeywordRecord) {
            $newInstance = ResourceKeyword::findOrNew($ResourceKeywordRecord->id);
            $newInstance->resource_id = $ResourceKeywordRecord->resource_id;
            $newInstance->tid = $ResourceKeywordRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Learning Resource Types
        foreach ($diffResourceLearningResourceTypeRecords as $ResourceLearningResourceTypeRecord) {
            $newInstance = ResourceLearningResourceType::findOrNew($ResourceLearningResourceTypeRecord->id);
            $newInstance->resource_id = $ResourceLearningResourceTypeRecord->resource_id;
            $newInstance->tid = $ResourceLearningResourceTypeRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Learning Resource Types
        foreach ($diffResourceEducationalUseRecords as $ResourceEducationalUseRecord) {
            $newInstance = ResourceEducationalUse::findOrNew($ResourceEducationalUseRecord->id);
            $newInstance->resource_id = $ResourceEducationalUseRecord->resource_id;
            $newInstance->tid = $ResourceEducationalUseRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Translation Rights
        foreach ($diffResourceTranslationRightRecords as $ResourceTranslationRightRecord) {
            $newInstance = ResourceTranslationRight::findOrNew($ResourceTranslationRightRecord->id);
            $newInstance->resource_id = $ResourceTranslationRightRecord->resource_id;
            $newInstance->tid = $ResourceTranslationRightRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Educational Resource Records
        foreach ($diffResourceEducationalResourceRecords as $ResourceEducationalResourceRecord) {
            $newInstance = ResourceEducationalResource::findOrNew($ResourceEducationalResourceRecord->id);
            $newInstance->resource_id = $ResourceEducationalResourceRecord->resource_id;
            $newInstance->tid = $ResourceEducationalResourceRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Copyright holders
        foreach ($diffResourceCopyrightHolderRecords as $ResourceCopyrightHolderRecord) {
            $newInstance = ResourceEducationalResource::findOrNew($ResourceCopyrightHolderRecord->id);
            $newInstance->resource_id = $ResourceCopyrightHolderRecord->resource_id;
            $newInstance->tid = $ResourceCopyrightHolderRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Creative Commons
        foreach ($diffResourceCreativeCommonRecords as $ResourceCreativeCommonRecord) {
            $newInstance = ResourceCreativeCommon::findOrNew($ResourceCreativeCommonRecord->id);
            $newInstance->resource_id = $ResourceCreativeCommonRecord->resource_id;
            $newInstance->tid = $ResourceCreativeCommonRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Share Permission
        foreach ($diffResourceSharePermissionRecords as $ResourceSharePermissionRecord) {
            $newInstance = ResourceSharePermission::findOrNew($ResourceSharePermissionRecord->id);
            $newInstance->resource_id = $ResourceSharePermissionRecord->resource_id;
            $newInstance->tid = $ResourceSharePermissionRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Author
        foreach ($diffResourceAuthorRecords as $ResourceAuthorRecord) {
            $newInstance = ResourceAuthor::findOrNew($ResourceAuthorRecord->id);
            $newInstance->resource_id = $ResourceAuthorRecord->resource_id;
            $newInstance->tid = $ResourceAuthorRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Publisher
        foreach ($diffResourcePublisherRecords as $ResourcePublisherRecord) {
            $newInstance = ResourcePublisher::findOrNew($ResourcePublisherRecord->id);
            $newInstance->resource_id = $ResourcePublisherRecord->resource_id;
            $newInstance->tid = $ResourcePublisherRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Translators
        foreach ($diffResourceTranslatorRecords as $ResourceTranslatorRecord) {
            $newInstance = ResourceTranslator::findOrNew($ResourceTranslatorRecord->id);
            $newInstance->resource_id = $ResourceTranslatorRecord->resource_id;
            $newInstance->tid = $ResourceTranslatorRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        //Resource Translators
        foreach ($diffResourceIamAuthorRecords as $ResourceIamAuthorRecord) {
            $newInstance = ResourceTranslator::findOrNew($ResourceIamAuthorRecord->id);
            $newInstance->resource_id = $ResourceIamAuthorRecord->resource_id;
            $newInstance->tid = $ResourceIamAuthorRecord->tid;
            $newInstance->timestamps = false;
            $newInstance->save();
        }

        return redirect('admin/sync');
    }
}
