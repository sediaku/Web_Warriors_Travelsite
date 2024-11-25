
document.querySelectorAll('.wishlist[name="add-to-wishlist"]').forEach(button => {
    button.addEventListener('click', function () {
        
        const parentDiv = this.closest('.left');

        
        const locationName = parentDiv.querySelector('.name').textContent.trim();

        
        fetch('../functions/addtowishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ location_name: locationName })
        })
        .then(response => response.text())
        .then(data => {
            alert(data); 
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

