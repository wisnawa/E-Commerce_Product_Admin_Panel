$(document).ready(function () {
  $('a.delete').click(function (e) {
    if (!confirm('Are you sure?')) {
      e.preventDefault();
      return false;
    }
    return true;
  });
});
