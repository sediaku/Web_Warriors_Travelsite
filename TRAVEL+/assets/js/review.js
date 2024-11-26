document.addEventListener('DOMContentLoaded', function(){
    const modal = document.getElementById('reviewModal');
    const addReviewBtn = document.querySelector('.add-review-btn');
    const closeBtn = document.querySelector('.close-modal');
    const cancelBtn = document.querySelector('.cancel-review');
    const submitBtn = document.querySelector('.submit-review');
    const reviewForm = document.getElementById('reviewForm');
    const stars = document.querySelectorAll('.star');
    const reviewText = document.getElementById('reviewText');
    
    // Get location ID from the page
    const locationId = addReviewBtn.getAttribute('data-location-id');

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
        stars.forEach(star => {
            star.innerHTML = '&#9734;';
            star.classList.remove('active');
        });
        submitBtn.disabled = true;
    }

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    window.addEventListener('click', function(event){
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
            if (starRating <= rating) {
                star.innerHTML = '&#9733;'; // Filled star
                star.classList.add('active');
            } else {
                star.innerHTML = '&#9734;'; // Empty star
                star.classList.remove('active');
            }
        });
    }

    function validateForm(){
        const isValid = currentRating > 0 && reviewText.value.trim().length > 0;
        submitBtn.disabled = !isValid;
    }

    reviewText.addEventListener('input', validateForm);

    reviewForm.addEventListener('submit', function(e){
        e.preventDefault();
        
        // Disable submit button to prevent multiple submissions
        submitBtn.disabled = true;
        
        // Prepare review data
        const review = {
            location_id: locationId,
            rating: currentRating,
            text: reviewText.value.trim()
        };

        // Send review to server
        fetch('../actions/review-action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(review)
        })
        .then(response => {
            // Check if response is OK
            if (!response.ok) {
                // Try to parse error message from JSON
                return response.json().then(errorData => {
                    throw new Error(errorData.error || 'Unknown error occurred');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Review submitted successfully!');
                closeModal();
                // Optionally, you could refresh reviews or update page
                location.reload();
            } else {
                throw new Error(data.error || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error: ' + error.message);
            // Re-enable submit button
            submitBtn.disabled = false;
        });
    });
});