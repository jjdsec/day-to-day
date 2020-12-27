
// create page
var page = document.createElement("div");
page.className = "Page";
page.name = "Tasks";
page.appendChild(document.createTextNode("this is the tasks page"));
document.getElementById("page-view").appendChild(page);