/**
 * Smart Lazy Loading System
 * Tối ưu hóa tải ảnh với adaptive loading và progressive enhancement
 */

class SmartLazyLoader {
    constructor(options = {}) {
        this.options = {
            rootMargin: '50px 0px',
            threshold: 0.01,
            enableBlur: true,
            fadeInDuration: 300,
            retryAttempts: 3,
            retryDelay: 1000,
            enableAdaptiveLoading: true,
            enableProgressiveLoading: true,
            batchSize: 6,
            ...options
        };
        
        this.observer = null;
        this.connectionType = this.getConnectionType();
        this.loadedImages = new Set();
        this.failedImages = new Set();
        this.retryCount = new Map();
        
        this.init();
    }
    
    init() {
        if ('IntersectionObserver' in window) {
            this.createObserver();
            this.observeImages();
        } else {
            // Fallback cho browsers cũ
            this.loadAllImages();
        }
        
        // Listen for new images added dynamically
        this.observeNewImages();
        
        // Adaptive loading based on connection
        if (this.options.enableAdaptiveLoading) {
            this.setupAdaptiveLoading();
        }
    }
    
    createObserver() {
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    this.observer.unobserve(entry.target);
                }
            });
        }, {
            rootMargin: this.options.rootMargin,
            threshold: this.options.threshold
        });
    }
    
    observeImages() {
        const images = document.querySelectorAll('img[data-src], [data-bg]');
        images.forEach(element => {
            if (!this.loadedImages.has(element)) {
                this.observer.observe(element);
            }
        });
    }
    
    observeNewImages() {
        // MutationObserver để theo dõi ảnh mới được thêm
        if ('MutationObserver' in window) {
            const mutationObserver = new MutationObserver((mutations) => {
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) { // Element node
                            const images = node.querySelectorAll ? 
                                node.querySelectorAll('img[data-src], [data-bg]') : [];
                            
                            images.forEach(img => {
                                if (!this.loadedImages.has(img)) {
                                    this.observer.observe(img);
                                }
                            });
                            
                            // Check if the node itself is a lazy image
                            if (node.hasAttribute && (node.hasAttribute('data-src') || node.hasAttribute('data-bg'))) {
                                if (!this.loadedImages.has(node)) {
                                    this.observer.observe(node);
                                }
                            }
                        }
                    });
                });
            });
            
            mutationObserver.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    async loadImage(element) {
        if (this.loadedImages.has(element)) return;
        
        const isBackgroundImage = element.hasAttribute('data-bg');
        const src = element.getAttribute(isBackgroundImage ? 'data-bg' : 'data-src');
        
        if (!src) return;
        
        try {
            // Show loading state
            this.showLoadingState(element);
            
            // Preload image
            await this.preloadImage(src);
            
            // Apply image
            if (isBackgroundImage) {
                this.applyBackgroundImage(element, src);
            } else {
                this.applyImage(element, src);
            }
            
            // Mark as loaded
            this.loadedImages.add(element);
            this.hideLoadingState(element);
            
            // Trigger custom event
            element.dispatchEvent(new CustomEvent('imageLoaded', { detail: { src } }));
            
        } catch (error) {
            this.handleImageError(element, src, error);
        }
    }
    
    preloadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            
            img.onload = () => resolve(img);
            img.onerror = () => reject(new Error(`Failed to load image: ${src}`));
            
            // Apply adaptive quality based on connection
            const adaptedSrc = this.getAdaptedImageSrc(src);
            img.src = adaptedSrc;
        });
    }
    
    applyImage(img, src) {
        // Remove blur effect
        if (this.options.enableBlur) {
            img.classList.remove('blur-placeholder');
        }
        
        // Set source
        img.src = this.getAdaptedImageSrc(src);
        
        // Handle srcset
        const srcset = img.getAttribute('data-srcset');
        if (srcset) {
            img.srcset = srcset;
            img.removeAttribute('data-srcset');
        }
        
        // Add loaded class with animation
        img.classList.add('loaded');
        
        // Remove data attributes
        img.removeAttribute('data-src');
    }
    
    applyBackgroundImage(element, src) {
        // Remove blur effect
        if (this.options.enableBlur) {
            element.classList.remove('blur-placeholder-bg');
        }
        
        // Set background image
        const adaptedSrc = this.getAdaptedImageSrc(src);
        element.style.backgroundImage = `url('${adaptedSrc}')`;
        
        // Add loaded class
        element.classList.add('loaded');
        
        // Remove data attribute
        element.removeAttribute('data-bg');
    }
    
    showLoadingState(element) {
        const loadingIndicator = element.parentElement?.querySelector('[data-loading="true"]');
        if (loadingIndicator) {
            loadingIndicator.classList.remove('hidden');
        }
        
        // Hide skeleton
        const skeleton = element.parentElement?.querySelector('[data-skeleton="true"]');
        if (skeleton) {
            skeleton.style.opacity = '1';
        }
    }
    
    hideLoadingState(element) {
        const loadingIndicator = element.parentElement?.querySelector('[data-loading="true"]');
        if (loadingIndicator) {
            loadingIndicator.classList.add('hidden');
        }
        
        // Hide skeleton
        const skeleton = element.parentElement?.querySelector('[data-skeleton="true"]');
        if (skeleton) {
            skeleton.style.opacity = '0';
            setTimeout(() => {
                skeleton.style.display = 'none';
            }, this.options.fadeInDuration);
        }
    }
    
    async handleImageError(element, src, error) {
        const retryKey = element;
        const currentRetries = this.retryCount.get(retryKey) || 0;
        
        if (currentRetries < this.options.retryAttempts) {
            // Retry after delay
            this.retryCount.set(retryKey, currentRetries + 1);
            
            setTimeout(() => {
                this.loadImage(element);
            }, this.options.retryDelay * (currentRetries + 1));
            
            return;
        }
        
        // Mark as failed
        this.failedImages.add(element);
        this.hideLoadingState(element);
        
        // Show error state
        this.showErrorState(element);
        
        // Trigger error event
        element.dispatchEvent(new CustomEvent('imageError', { 
            detail: { src, error: error.message } 
        }));
    }
    
    showErrorState(element) {
        if (element.tagName === 'IMG') {
            element.style.display = 'none';
            
            // Create error placeholder
            const errorDiv = document.createElement('div');
            errorDiv.className = 'flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 text-gray-400 h-full';
            errorDiv.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                    <p class="text-sm font-medium">Lỗi tải ảnh</p>
                </div>
            `;
            
            element.parentElement?.appendChild(errorDiv);
        }
    }
    
    getConnectionType() {
        if ('connection' in navigator) {
            return navigator.connection.effectiveType || '4g';
        }
        return '4g'; // Default to 4g
    }
    
    getAdaptedImageSrc(src) {
        if (!this.options.enableAdaptiveLoading) return src;
        
        // Adaptive quality based on connection
        const qualityMap = {
            'slow-2g': 60,
            '2g': 70,
            '3g': 80,
            '4g': 90
        };
        
        const quality = qualityMap[this.connectionType] || 85;
        
        // If src already has quality parameter, replace it
        if (src.includes('quality=')) {
            return src.replace(/quality=\d+/, `quality=${quality}`);
        }
        
        // Add quality parameter
        const separator = src.includes('?') ? '&' : '?';
        return `${src}${separator}quality=${quality}`;
    }
    
    setupAdaptiveLoading() {
        // Listen for connection changes
        if ('connection' in navigator) {
            navigator.connection.addEventListener('change', () => {
                this.connectionType = this.getConnectionType();
            });
        }
    }
    
    loadAllImages() {
        // Fallback for browsers without IntersectionObserver
        const images = document.querySelectorAll('img[data-src], [data-bg]');
        images.forEach(element => this.loadImage(element));
    }
    
    // Progressive loading for galleries
    loadImageBatch(images, batchIndex = 0) {
        const batchSize = this.options.batchSize;
        const startIndex = batchIndex * batchSize;
        const endIndex = startIndex + batchSize;
        const batch = images.slice(startIndex, endIndex);
        
        batch.forEach(img => this.loadImage(img));
        
        // Load next batch when user scrolls near the end
        if (endIndex < images.length) {
            const lastImage = batch[batch.length - 1];
            if (lastImage) {
                const nextBatchObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.loadImageBatch(images, batchIndex + 1);
                            nextBatchObserver.unobserve(entry.target);
                        }
                    });
                }, { rootMargin: '200px 0px' });
                
                nextBatchObserver.observe(lastImage);
            }
        }
    }
    
    // Public methods
    refresh() {
        this.observeImages();
    }
    
    destroy() {
        if (this.observer) {
            this.observer.disconnect();
        }
    }
    
    getStats() {
        return {
            loaded: this.loadedImages.size,
            failed: this.failedImages.size,
            connectionType: this.connectionType
        };
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.smartLazyLoader = new SmartLazyLoader({
        enableBlur: true,
        enableAdaptiveLoading: true,
        enableProgressiveLoading: true,
        batchSize: 6
    });
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SmartLazyLoader;
}
