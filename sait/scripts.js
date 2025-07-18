// В projects.html
function showAssemblySteps() {
  const steps = document.querySelectorAll('.assembly-step');
  let currentStep = 0;

  function showStep(index) {
    steps.forEach((step, i) => {
      step.style.display = i === index ? 'block' : 'none';
    });
  }

  document.getElementById('next-step').addEventListener('click', () => {
    currentStep = (currentStep + 1) % steps.length;
    showStep(currentStep);
  });

  document.getElementById('prev-step').addEventListener('click', () => {
    currentStep = (currentStep - 1 + steps.length) % steps.length;
    showStep(currentStep);
  });

  showStep(0);
}