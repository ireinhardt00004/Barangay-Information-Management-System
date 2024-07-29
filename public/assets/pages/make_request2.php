<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dropdown View Change Example</title>
<style>
  .view {
    display: none; /* Hide all views by default */
  }
</style>
</head>
<body>

<select id="myDropdown" onchange="changeView()">
  <option value="view1">View 1</option>
  <option value="view2">View 2</option>
  <option value="view3">View 3</option>
</select>

<div id="view1" class="view">
  <h2>View 1 Content</h2>
  <p>This is the content of view 1.</p>
</div>

<div id="view2" class="view">
  <h2>View 2 Content</h2>
  <p>This is the content of view 2.</p>
</div>

<div id="view3" class="view">
  <h2>View 3 Content</h2>
  <p>This is the content of view 3.</p>
</div>

<script>
function changeView() {
  var dropdown = document.getElementById("myDropdown");
  var selectedView = dropdown.options[dropdown.selectedIndex].value;
  
  // Hide all views
  var views = document.getElementsByClassName("view");
  for (var i = 0; i < views.length; i++) {
    views[i].style.display = "none";
  }
  
  // Show the selected view
  var selectedViewElement = document.getElementById(selectedView);
  if (selectedViewElement) {
    selectedViewElement.style.display = "block";
  }
}
</script>

</body>
</html>
