function changePage(pageName) {
    var view = document.getElementById("page-view");
    var foundPage = false;
    Array.from(view.children).forEach(page => {
        if (page.name == pageName) {
            if (!page.classList.contains("currentPage"))
                page.classList.add("currentPage");
                foundPage = true;
        } else {
            if (page.classList.contains("currentPage"))
                page.classList.remove("currentPage");
        }
    });
    if (!foundPage && pageName != "tasks") 
        changePage("tasks");   
}