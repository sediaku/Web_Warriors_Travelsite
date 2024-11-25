document.addEventListener('DOMContentLoaded', function(){
    const modal = document.getElementById('reviewModal');
    const addReviewBtn = document.querySelector('.add-review-btn');
    const closeBtn = document.querySelector('.close-modal');
    const cancelBtn = document.querySelector('.cancel-review');
    const submitBtn = document.querySelector('.submit-review');
    const reviewForm = document.getElementById('reviewForm');
    const stars = document.querySelectorAll('.star');
    const reviewText = document.getElementById('reviewText');

    let currentRating = 0;

    addReviewBtn.addEventListener('click', function(){
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });

    function closeModal(){
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        reviewForm.reset();
        currentRating = 0;
        stars.forEach(star => star.classList.remove('active'));
        submitBtn.disabled = true;
    }

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click',closeModal);

    window.addEventListener('click',function(event){
        if (event.target == modal){
            closeModal();
        }
    });

    stars.forEach(star => {
        star.addEventListener('mouseover', function(){
            const rating = this.dataset.rating;
            highlightStars(rating);
        });
        star.addEventListener('mouseout', function(){
            highlightStars(currentRating);
        });
        star.addEventListener('click', function(){
            currentRating = this.dataset.rating;
            highlightStars(currentRating);
            validateForm();
        });
    });

    function highlightStars(rating){
        stars.forEach(star => {
            const starRating = star.dataset.rating;
            star.classList.toggle('active', starRating <= rating);
        });
    }

    function validateForm(){
        const isValid = currentRating > 0 && reviewText.value.trim().length > 0;
        submitBtn.disabled = !isValid;
    }

    reviewText.addEventListener('input', validateForm);

    reviewForm.addEventListener('submit', function(e){
        e.preventDefault();
        const review = {
            rating: currentRating,
            text: reviewText.value.trim()
        };

        console.log('Review submitted:', review);

        closeModal();

        alert('Review submitted successfully!');
    });
});