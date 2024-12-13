document.addEventListener('DOMContentLoaded', function () {
    const disclosureElements = document.querySelectorAll('details');
    disclosureElements.forEach((details) => {
        const summary = details.querySelector('summary');
        if (summary) {
            summary.addEventListener('click', (e) => {
                e.preventDefault();
                details.toggleAttribute('open');
            });
        }
    });
});