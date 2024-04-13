const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const successMessage = document.getElementById('successMessage');
    const failedMessage = document.getElementById('failedMessage');

    // If deleteSuccess is present and equals 1, show the success message for 2 seconds
    if (success === '1') {
        successMessage.textContent = 'Added Successfully';
        successMessage.removeAttribute('hidden');
        setTimeout(() => {
            successMessage.setAttribute('hidden', true);
        }, 2000);
    } else if (success === '0') {
        failedMessage.textContent = 'Failed To Add';
        failedMessage.removeAttribute('hidden');
        setTimeout(() => {
            failedMessage.setAttribute('hidden', true);
        }, 2000); 
    } else if (success === '2') {
        successMessage.textContent = 'Updated Successfully';
        successMessage.removeAttribute('hidden');
        setTimeout(() => {
            successMessage.setAttribute('hidden', true);
        }, 2000);
    } else if (success === '3') {
        failedMessage.textContent = 'Failed To Update';
        failedMessage.removeAttribute('hidden');
        setTimeout(() => {
            failedMessage.setAttribute('hidden', true);
        }, 2000); 
    } else if (success === '4') {
        successMessage.textContent = 'Deleted Successfully';
        successMessage.removeAttribute('hidden');
        setTimeout(() => {
            successMessage.setAttribute('hidden', true);
        }, 2000);
    }else if (success === '5') {
        failedMessage.textContent = 'Failed To Delete';
        failedMessage.removeAttribute('hidden');
        setTimeout(() => {
            failedMessage.setAttribute('hidden', true);
        }, 2000); 
    } else if (success === '6') {
        successMessage.textContent = 'Order Saved';
        successMessage.removeAttribute('hidden');
        setTimeout(() => {
            successMessage.setAttribute('hidden', true);
        }, 2000);
    } else if (success === '7') {
        failedMessage.textContent = 'Failed To Order, Try Again';
        failedMessage.removeAttribute('hidden');
        setTimeout(() => {
            failedMessage.setAttribute('hidden', true);
        }, 2000); 
    }