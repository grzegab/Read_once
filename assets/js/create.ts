document.querySelector('.message_form_submit').addEventListener('click', () => {
    const messageForm = <HTMLFormElement>document.querySelector('form[name="message_form"]');
    const dangerBorderClass = 'border-danger';
    let messageInput = <HTMLInputElement>document.getElementById('message_form_content');
    let messageInputValue = messageInput.value;

    if (messageInputValue && messageInputValue !== '') {
        messageInput.classList.remove(dangerBorderClass);
        messageForm.submit();
    } else {
        messageInput.classList.add(dangerBorderClass);
    }
});