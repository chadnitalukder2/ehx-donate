class DownloadReceipt {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        const downloadButton = document.querySelector('#ehxdo_download-receipt-btn');
        downloadButton.addEventListener('click', (e) => this.downloadReceipt(e));
    }

    downloadReceipt(e) {
        document.querySelector('#ehxdo_download-receipt-btn').textContent = 'Downloading...';
        const donationId = document.querySelector('.ehxdo_reciept_container').dataset.donationId;
        e.preventDefault();
    
        fetch(`${window?.EHXDonate?.restUrl}receipt/download/${donationId}`, {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
        })
        .then(response => response?.json())
        .then(data => {
            document.querySelector('#ehxdo_download-receipt-btn').textContent = 'Download Receipt ⬇️';
            console.log({data});
            if (data?.pdf) {
                // Convert base64 to blob
                const byteCharacters = atob(data.pdf);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], {type: 'application/pdf'});
                
                // Download the blob
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = data?.filename || 'donation_receipt.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            } else {
                console.error('PDF generation failed:', data.message);
            }
        })
        .catch(error => {
                        document.querySelector('#ehxdo_download-receipt-btn').textContent = 'Download Receipt ⬇️';
            console.error('Download error:', error);
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new DownloadReceipt();
});