function filtrages() {
  let input = document.getElementById("recherche");
  let selectTypeRestaurant = document.getElementById("TypeRestaurant");
  let selectTypeCuisine = document.getElementById("TypeCuisine");
  let checkboxes = document.querySelectorAll(".menu-content input[type='checkbox']");
  let restaurants = document.querySelectorAll(".restaurant");

  window.addEventListener("load", () => {
    if (localStorage.getItem("searchText")) {
      input.value = localStorage.getItem("searchText");
    }
    if (localStorage.getItem("selectedType")) {
      selectTypeRestaurant.value = localStorage.getItem("selectedType");
    }
    if (localStorage.getItem("selectedCuisine")) {
      selectTypeCuisine.value = localStorage.getItem("selectedCuisine");
    }
    checkboxes.forEach(checkbox => {
      if (localStorage.getItem(checkbox.id) === "true") {
        checkbox.checked = true;
      }
    });
    filterRestaurants();
  });

  window.addEventListener("beforeunload", () => {
    localStorage.setItem("searchText", input.value);
    localStorage.setItem("selectedType", selectTypeRestaurant.value);
    localStorage.setItem("selectedCuisine", selectTypeCuisine.value);
    checkboxes.forEach(checkbox => {
      localStorage.setItem(checkbox.id, checkbox.checked);
    });
  });

  input.addEventListener("input", filterRestaurants);
  selectTypeRestaurant.addEventListener("change", filterRestaurants);
  selectTypeCuisine.addEventListener("change", filterRestaurants);
  checkboxes.forEach(checkbox => checkbox.addEventListener("change", filterRestaurants));

  function filterRestaurants() {
    let searchText = input.value.toLowerCase();
    let selectedType = selectTypeRestaurant.value.toLowerCase();
    let selectedCuisine = selectTypeCuisine.value.toLowerCase();
    let filters = Array.from(checkboxes).filter(checkbox => checkbox.checked).map(checkbox => checkbox.id);

    restaurants.forEach((restaurant) => {
      let name = restaurant.querySelector("h3").textContent.toLowerCase();
      let type = restaurant.querySelector(".typeRestaurant").textContent.toLowerCase();
      let cuisines = Array.from(restaurant.querySelectorAll(".typeCuisine div")).map((div) => div.textContent.toLowerCase());
      let matchesCuisine = selectedCuisine === "" || cuisines.includes(selectedCuisine);
      let matchesType = selectedType === "" || type.includes(selectedType);
      let matchesSearch = searchText === "" || name.includes(searchText);

      let matchesFilters = filters.every(filter => {
        let filterValue = restaurant.querySelector(`.${filter}`).textContent.toLowerCase();
        return filterValue === "1";
      });

      if (matchesSearch && matchesType && matchesCuisine && matchesFilters) {
        restaurant.parentElement.style.display = "";
      } else {
        restaurant.parentElement.style.display = "none";
      }
    });
  }
}

filtrages();