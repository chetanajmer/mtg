document.addEventListener('DOMContentLoaded', function() {
  // Occasion selection
  const occasionCards = document.querySelectorAll('.occasion-card');
  occasionCards.forEach(card => {
    card.addEventListener('click', function() {
      occasionCards.forEach(c => c.classList.remove('active'));
      this.classList.add('active');
      const occasion = this.dataset.occasion;
      const occasionName = this.querySelector('span').textContent;
      document.getElementById('selectedOccasion').textContent = occasionName;
      // Update recommendations based on occasion
    });
  });

  // Preference chips
  const preferenceChips = document.querySelectorAll('.preference-chip');
  preferenceChips.forEach(chip => {
    chip.addEventListener('click', function() {
      this.classList.toggle('active');
    });
  });

  // Show Recommendations button
  const showRecommendationsBtn = document.querySelector('.btn-show-recommendations');
  if (showRecommendationsBtn) {
    showRecommendationsBtn.addEventListener('click', function() {
      const teamSize = document.getElementById('teamSize').value;
      const budgetRange = document.getElementById('budgetRange').value;
      
      if (!teamSize || !budgetRange) {
        alert('Please select team size and budget range to see recommendations.');
        return;
      }
      
      // Scroll to recommendations
      const recommendationsSection = document.getElementById('recommendations');
      if (recommendationsSection) {
        recommendationsSection.scrollIntoView({ behavior: 'smooth' });
      }
      
      // Update recommendations based on selections (frontend simulation)
      alert(`Showing recommendations for ${teamSize} team with ${budgetRange} budget...`);
    });
  }

  // Select Bundle buttons
  const selectBundleBtns = document.querySelectorAll('.btn-select-bundle');
  selectBundleBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const bundle = this.dataset.bundle;
      const teamSize = document.getElementById('teamSize').value;
      
      if (!teamSize) {
        alert('Please select team size to calculate total cost.');
        return;
      }
      
      alert(`You selected the ${bundle} bundle. Proceeding to cart...`);
      // Navigate to cart or enquiry
      window.location.href = '/cart';
    });
  });

  // View More Options button
  const viewMoreBtn = document.querySelector('.btn-view-more');
  if (viewMoreBtn) {
    viewMoreBtn.addEventListener('click', function() {
      alert('Loading more gift bundle options...');
    });
  }

  // Start Planning button
  const startPlanningBtn = document.querySelector('.btn-start-planning');
  if (startPlanningBtn) {
    startPlanningBtn.addEventListener('click', function() {
      window.location.href = '/corporate-portal';
    });
  }
});
