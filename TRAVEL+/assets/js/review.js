document.addEventListener('DOMContentLoaded', function(){
    const modal = document.getElementById('reviewModal');
    const addReviewBtn = document.querySelector('.add-review-btn');
    const closeBtn = document.querySelector('.close-modal');
    const cancelBtn = document.querySelector('.cancel-review');
    const submitBtn = document.querySelector('.submit-review');
    const reviewForm = document.getElementById('reviewForm');
    const stars = document.querySelectorAll('.star');
    const reviewText = document.getElementById('reviewText');
    const reviewsContainer = document.getElementById('reviewsContainer');
    
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
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.error || 'Unknown error occurred');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Remove "No reviews" message if it exists
                const noReviewsMsg = document.querySelector('.no-reviews');
                if (noReviewsMsg) {
                    noReviewsMsg.remove();
                }

                // Create new review element
                const newReview = document.createElement('div');
                newReview.classList.add('review-item');
                newReview.setAttribute('data-review-id', data.review_id);

                // Generate star rating HTML
                const starRating = Array.from({length: 5}, (_, i) => 
                    i < currentRating ? '&#9733;' : '&#9734;'
                ).join('');

                // Set inner HTML for new review
                newReview.innerHTML = `
                    <div class="review-header">
                        <div class="reviewer-info">
                            <span class="reviewer-name">${data.username}</span>
                            <div class="review-rating">
                                ${starRating}
                            </div>
                        </div>
                        <span class="review-date">${new Date().toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        })}</span>
                    </div>
                    <div class="review-content">
                        ${review.text}
                    </div>
                `;

                // Insert new review at the top of reviews container
                if (reviewsContainer.firstChild) {
                    reviewsContainer.insertBefore(newReview, reviewsContainer.firstChild);
                } else {
                    reviewsContainer.appendChild(newReview);
                }

                // Update average rating 
                // Update average rating 
                const averageRatingElement = document.querySelector('.rating span');
                if (averageRatingElement && data.new_average_rating !== undefined) {
                    const newAverageRating = parseFloat(data.new_average_rating);
                    if (!isNaN(newAverageRating)) {
                        averageRatingElement.textContent = newAverageRating.toFixed(1);
                    }
                }
                // Close modal and reset form
                alert('Review submitted successfully!');
                closeModal();
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