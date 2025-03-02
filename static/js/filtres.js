function filtrages() {
  let input = document.getElementById("recherche");
  let selectTypeRestaurant = document.getElementById("TypeRestaurant");
  let selectTypeCuisine = document.getElementById("TypeCuisine");
  let restaurants = document.querySelectorAll(".restaurant");

  input.addEventListener("input", filterRestaurants);
  selectTypeRestaurant.addEventListener("change", filterRestaurants);
  selectTypeCuisine.addEventListener("change", filterRestaurants);

  function filterRestaurants() {
    let searchText = input.value.toLowerCase();
    let selectedType = selectTypeRestaurant.value.toLowerCase();
    let selectedCuisine = selectTypeCuisine.value.toLowerCase();
    restaurants.forEach((restaurant) => {
      let name = restaurant.querySelector("h3").textContent.toLowerCase();
      let type = restaurant
        .querySelector(".typeRestaurant")
        .textContent.toLowerCase();
      let cuisines = Array.from(
        restaurant.querySelectorAll(".typeCuisine div")
      ).map((div) => div.textContent.toLowerCase());

      if (type === "fast-food") {
        type = "fast_food";
      }
      if (selectedType === "fast-food") {
        selectedType = "fast_food";
      }

      let matchesCuisine =
        selectedCuisine === "" || cuisines.includes(selectedCuisine);

      if (
        (searchText === "" || name.includes(searchText)) &&
        (selectedType === "" || type.includes(selectedType)) &&
        matchesCuisine
      ) {
        restaurant.parentElement.style.display = "";
      } else {
        restaurant.parentElement.style.display = "none";
      }
    });
  }
}

filtrages();
