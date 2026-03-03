import './bootstrap';
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('studentInfoForm');
  const btn = document.getElementById('proceedBtn');

  if (!form || !btn) return;

  form.addEventListener('submit', () => {
    btn.disabled = true;
    btn.classList.add('opacity-80', 'cursor-not-allowed');
    btn.innerHTML = 'Processing...';
  });
});

