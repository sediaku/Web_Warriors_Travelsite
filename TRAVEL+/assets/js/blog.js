document.addEventListener('DOMContentLoaded', function(){
    const modal = document.getElementById('commentModal');
    const openBtn = document.querySelector('.add-comment-btn');
    const closeBtn = document.querySelector('.close');
    const cancelBtn = document.querySelector('.btn-cancel');
    const submitBtn = document.querySelector('.btn-submit');
    const commentForm = document.getElementById('commentForm');
    const textarea = document.querySelector('.comment-textarea');

    openBtn.addEventListener('click',function(){
        modal.style.display='block';
    });

    function closeModal(){
        modal.style.display='none';
        textarea.value = '';
    }

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click',closeModal);

    window.addEventListener('click', function(event){
        if (event.target === modal){
            closeModal();
        }
    });

    commentForm.addEventListener('submit', function(e){
        e.preventDefault();
        const comment = textarea.value.trim();

        if (comment){
            console.log('Submitted comment:', comment);

            const commentElement = document.createElement('div');
            commentElement.textContent = comment;
            document.querySelector('.comments').appendChild(commentElement);

            closeModal();
        }
    });

    textarea.addEventListener('input', function(){
        submitBtn.disabled = !this.value.trim();
    });
});