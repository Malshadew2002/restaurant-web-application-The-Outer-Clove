document.addEventListener('DOMContentLoaded', function () {
    const navigation = document.querySelector('.navigation');
    const toggleButton = document.querySelector('.toggle-button');

    toggleButton.addEventListener('click', function () {
        navigation.classList.toggle('show');
    });
});

