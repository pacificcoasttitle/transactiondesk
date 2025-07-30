$(document).ready(function () {

    $('#download_estimate').click(function () {
        console.log('hi');
        var pdf = new jsPDF('', 'pt', 'a4');

        var element = document.getElementById('artcle_main');
        html2pdf(element, {
            margin: 1,
            filename: 'feeEstimation.pdf',
            // image: {type: 'jpeg', quality: 1},
            html2canvas: {
                scale: 4,
                logging: false
            },
            // jsPDF: {unit: 'mm', format: 'a4', orientation: 'p'}

        });
        
    });
});

function base64toBlob(base64Data, contentType) {
    contentType = contentType || '';
    var sliceSize = 1024;
    var byteCharacters = atob(base64Data);
    var bytesLength = byteCharacters.length;
    var slicesCount = Math.ceil(bytesLength / sliceSize);
    var byteArrays = new Array(slicesCount);

    for (var sliceIndex = 0; sliceIndex < slicesCount; ++sliceIndex) {
        var begin = sliceIndex * sliceSize;
        var end = Math.min(begin + sliceSize, bytesLength);

        var bytes = new Array(end - begin);
        for (var offset = begin, i = 0; offset < end; ++i, ++offset) {
            bytes[i] = byteCharacters[offset].charCodeAt(0);
        }
        byteArrays[sliceIndex] = new Uint8Array(bytes);
    }
    return new Blob(byteArrays, {
        type: contentType
    });
}
