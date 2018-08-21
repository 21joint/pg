import "../../scss/auth/login.scss";

$(document).ready(function () {
  init();
});

function init() {
  console.log("login module loaded");
}


module.hot.accept(
  console.error // Function to handle errors when evaluating the new version
);
