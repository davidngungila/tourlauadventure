document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item[data-image-url]');
    const lightbox = document.getElementById('galleryLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxDescription = document.getElementById('lightboxDescription');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');
    const lightboxOverlay = lightbox.querySelector('.lightbox-overlay');
    
    let currentIndex = 0;
    const images = Array.from(galleryItems).map(item => ({
        url: item.dataset.imageUrl,
        title: item.dataset.imageTitle || '',
        caption: item.dataset.imageCaption || ''
    }));

    // Open lightbox
    function openLightbox(index) {
        if (index < 0 || index >= images.length) return;
        currentIndex = index;
        const image = images[currentIndex];
        lightboxImage.src = image.url;
        lightboxTitle.textContent = image.title;
        lightboxDescription.textContent = image.caption;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Close lightbox
    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Navigate to previous image
    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        openLightbox(currentIndex);
    }

    // Navigate to next image
    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        openLightbox(currentIndex);
    }

    // Event listeners
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', () => openLightbox(index));
    });

    lightboxClose.addEventListener('click', closeLightbox);
    lightboxOverlay.addEventListener('click', closeLightbox);
    lightboxPrev.addEventListener('click', prevImage);
    lightboxNext.addEventListener('click', nextImage);

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('active')) return;
        
        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            prevImage();
        } else if (e.key === 'ArrowRight') {
            nextImage();
        }
    });

    // Prevent lightbox content from closing when clicking on image
    lightboxImage.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
