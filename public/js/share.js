// document.addEventListener('DOMContentLoaded', function () {
//     const copyButtons = document.querySelectorAll('.share-button__copy');

//     copyButtons.forEach((button) => {
//         button.addEventListener('click', () => {
//             const inputField = button.parentElement.querySelector('input');
//             inputField.select();
//             document.execCommand('copy');
//             alert('Link copied to clipboard!');
//         });
//     });
// });
document.addEventListener('DOMContentLoaded', function () {
    const shareButton = document.querySelector('.menu-link.to-share');
    if (navigator.share) {
        shareButton.addEventListener('click', async (e) => {
            e.preventDefault();
            try {
                await navigator.share({
                    title: document.title,
                    url: window.location.href
                });
            } catch (err) {
                console.error('Error sharing:', err);
            }
        });
    } else {
        console.log('Web Share API is not supported in this browser.');
    }
});