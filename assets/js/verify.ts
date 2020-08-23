document.querySelector('.verify_form_submit').addEventListener('click', () => {
    const verifyForm = <HTMLFormElement>document.querySelector('form[name="verify_form"]');
    const dangerBorderClass = 'border-danger';
    let messageInput = <HTMLInputElement>document.getElementById('verify_form_uuid');
    let messageInputValue = messageInput.value;

    if (messageInputValue && messageInputValue !== '') {
        messageInput.classList.remove(dangerBorderClass);
        verifyForm.submit();
    } else {
        messageInput.classList.add(dangerBorderClass);
    }
});