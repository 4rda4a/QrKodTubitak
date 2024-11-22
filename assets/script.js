const formControls = document.querySelectorAll('.form-control');
formControls.forEach(element => {
  element.classList.add('bg-dark', 'text-light');
});

setInterval(() => {
  document.getElementById("alertBtnId").remove();
}, 3000);

function alertRem() {
  document.getElementById("alertBtnId").remove();
}