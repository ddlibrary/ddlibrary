// Production-ready EPUB Viewer using epubjs
class EPUBViewer {
    constructor() {
        this.book = null;
        this.rendition = null;
        this.currentPage = 0;
        this.totalPages = 0;
        this.currentFontSize = 18;
        this.fontSizes = [12,14, 16, 18, 20, 22, 24,26,28,30];
        this.fontSizeIndex = 2;
        
        this.init();
    }

    async init() {
        try {
            document.getElementById('epubViewer').style.display = 'block';
            await this.loadEPUB();
        } catch (error) {
            console.error('Error initializing EPUB viewer:', error);
            this.showError('Failed to load EPUB file. Please check the file path and try again.');
        }
    }

    async loadEPUB() {
        try {
            await this.loadLibraries();
            const route = document.getElementById('app').dataset.fileRoute;
            this.book = new ePub(route);
            
            this.rendition = this.book.renderTo(document.getElementById('epubContent'), {
                width: '100%',
                height: '650px',
                flow: 'scrolled-doc',
                allowScriptedContent: true
            });
            
            await this.rendition.display();
            await this.loadMetadata();
            this.setupNavigation();
            this.updateControls();
            
            // Clear loading state - EPUB is now loaded
            this.clearLoadingState();
            
        } catch (error) {
            console.error('Error loading EPUB:', error);
            throw error;
        }
    }

    clearLoadingState() {
        // Remove the loading message and show the actual EPUB content
        const contentElement = document.getElementById('epubContent');
        if (contentElement) {
            // The epubjs rendition should already be rendering content here
            // Just ensure any loading indicators are cleared
            contentElement.style.minHeight = '600px';
        }
    }

    async loadLibraries() {
        try {
            // Load JSZip
            if (typeof JSZip === 'undefined') {
                const jszipSources = [
                    'https://unpkg.com/jszip@3.10.1/dist/jszip.min.js',
                    'https://cdn.jsdelivr.net/npm/jszip@3.10.1/dist/jszip.min.js'
                ];
                
                let jszipLoaded = false;
                for (const source of jszipSources) {
                    try {
                        const response = await fetch(source);
                        if (response.ok) {
                            const script = await response.text();
                            eval(script);
                            jszipLoaded = true;
                            break;
                        }
                    } catch (e) {
                        console.log(`Failed to load JSZip from ${source}`);
                    }
                }
                
                if (!jszipLoaded) {
                    throw new Error('Failed to load JSZip library');
                }
            }
            
            // Load epubjs
            if (typeof ePub === 'undefined') {
                const epubSources = [
                    'https://unpkg.com/epubjs@0.3.88/dist/epub.min.js',
                    'https://cdn.jsdelivr.net/npm/epubjs@0.3.88/dist/epub.min.js'
                ];
                
                let epubLoaded = false;
                for (const source of epubSources) {
                    try {
                        const response = await fetch(source);
                        if (response.ok) {
                            const script = await response.text();
                            eval(script);
                            epubLoaded = true;
                            break;
                        }
                    } catch (e) {
                        console.log(`Failed to load epubjs from ${source}`);
                    }
                }
                
                if (!epubLoaded) {
                    throw new Error('Failed to load epubjs library');
                }
            }
            
            if (typeof JSZip === 'undefined' || typeof ePub === 'undefined') {
                throw new Error('Required libraries failed to load');
            }
            
        } catch (error) {
            console.error('Error loading libraries:', error);
            throw new Error(`Failed to load required libraries: ${error.message}`);
        }
    }

    async loadMetadata() {
        try {
            if (this.book) {
                const metadata = await this.book.loaded.metadata;
                
                if (metadata.title) {
                    document.getElementById('epubTitle').textContent = metadata.title;
                }
                
                if (metadata.creator) {
                    document.getElementById('epubAuthor').textContent = metadata.creator;
                }
                
                const spine = this.book.spine;
                this.totalPages = spine.length;
                this.updateStatus();
            }
        } catch (error) {
            console.error('Error loading metadata:', error);
        }
    }

    setupNavigation() {
        if (this.rendition) {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') {
                    this.nextPage();
                } else if (e.key === 'ArrowLeft') {
                    this.previousPage();
                }
            });
        }
    }

    nextPage() {
        if (this.rendition && this.rendition.next) {
            this.rendition.next();
            this.currentPage++;
            this.updateProgress();
            this.updateStatus();
            this.updateControls();
        }
    }

    previousPage() {
        if (this.rendition && this.rendition.prev) {
            this.rendition.prev();
            this.currentPage--;
            this.updateProgress();
            this.updateStatus();
            this.updateControls();
        }
    }

    updateControls() {
        document.getElementById('prevBtn').disabled = this.currentPage === 0;
        document.getElementById('nextBtn').disabled = this.currentPage >= this.totalPages - 1;
    }

    updateProgress() {
        if (this.totalPages > 0) {
            const progress = ((this.currentPage + 1) / this.totalPages) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
        }
    }

    updateStatus() {
        if (this.totalPages > 0) {
         
            document.getElementById('epubStatus').textContent = 
                ` ${this.currentPage + 1} / ${this.totalPages}`;
        }
    }

    toggleFontSize() {
        this.fontSizeIndex = (this.fontSizeIndex + 1) % this.fontSizes.length;
        this.currentFontSize = this.fontSizes[this.fontSizeIndex];
        
        if (this.rendition && this.rendition.themes) {
            this.rendition.themes.fontSize(`${this.currentFontSize}px`);
        }
        
        document.getElementById('fontSizeBtn').textContent = 
            `Font: ${this.currentFontSize}px`;
    }

    showTableOfContents() {
        if (this.book && this.book.navigation) {
            this.book.navigation.then(nav => {
                let toc = '<h2>Table of Contents</h2>';
                
                if (nav.toc) {
                    nav.toc.forEach((item, index) => {
                        const isCurrent = index === this.currentPage ? ' (Current)' : '';
                        toc += `<p><a href="#" onclick="goToChapter('${item.href}')" style="color: #667eea; text-decoration: none;">${item.label}${isCurrent}</a></p>`;
                    });
                }
                
                document.getElementById('epubContent').innerHTML = toc;
            });
        } else {
            let toc = '<h2>Table of Contents</h2>';
            for (let i = 0; i < this.totalPages; i++) {
                const isCurrent = i === this.currentPage ? ' (Current)' : '';
                toc += `<p><a href="#" onclick="goToPage(${i})" style="color: #667eea; text-decoration: none;">Chapter ${i + 1}${isCurrent}</a></p>`;
            }
            document.getElementById('epubContent').innerHTML = toc;
        }
    }

    goToPage(pageIndex) {
        if (pageIndex >= 0 && pageIndex < this.totalPages) {
            this.currentPage = pageIndex;
            if (this.rendition && this.book) {
                this.book.spine.get(pageIndex).then(chapter => {
                    this.rendition.display(chapter.href);
                    this.updateProgress();
                    this.updateStatus();
                    this.updateControls();
                });
            }
        }
    }

    goToChapter(href) {
        if (this.rendition) {
            this.rendition.display(href);
            this.currentPage = Math.min(this.currentPage + 1, this.totalPages - 1);
            this.updateProgress();
            this.updateStatus();
            this.updateControls();
        }
    }

    showError(message) {
        document.getElementById('epubContent').innerHTML = 
            `<div class="epub-error">${message}</div>`;
    }
}

// Global functions for button clicks
let epubViewer;

function nextPage() {
    if (epubViewer) epubViewer.nextPage();
}

function previousPage() {
    if (epubViewer) epubViewer.previousPage();
}

function showTableOfContents() {
    if (epubViewer) epubViewer.showTableOfContents();
}

function toggleFontSize() {
    if (epubViewer) epubViewer.toggleFontSize();
}

function goToPage(pageIndex) {
    if (epubViewer) epubViewer.goToPage(pageIndex);
}

function goToChapter(href) {
    if (epubViewer) epubViewer.goToChapter(href);
}

// Initialize EPUB viewer when page loads
document.addEventListener('DOMContentLoaded', function() {
    epubViewer = new EPUBViewer();
});
