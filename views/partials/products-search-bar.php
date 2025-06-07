<div class="search-bar">

    <div class="search-item category dropdown">
        <button>All Categories</button>
        <div class="dropdown-options">
            <div class="option" href="#" data-value="0">Clothing</div>
            <div class="option" href="#" data-value="1">Electronics</div>
            <div class="option" href="#" data-value="2">Home & Garden</div>
            <div class="option" href="#" data-value="3">Books & Stationery</div>
            <div class="option" href="#" data-value="4">Toys</div>
            <div class="option" href="#" data-value="5">Beauty</div>
            <div class="option" href="#" data-value="6">Sports</div>
            <div class="option" href="#" data-value="7">Pets</div>
        </div>
        <svg class="arrow" viewBox="0 0 12 6" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 1L6 5L11 1" stroke="currentColor" stroke-width="1.8" fill="none" />
        </svg>
    </div>

    <div class="search-item search-field">
        <input type="text" maxlength="80" placeholder="What are you looking for?">
    </div>

    <div class="search-item search-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
        </svg>
    </div>

    <div class="search-item price dropdown">
        <button>Price</button>
        <div class="dropdown-options">
            <div class="range-selector">
                <span class="label">Min</span>
                <span>-</span>
                <span class="rand-symbol">R</span>
                <input
                    id="input-min"
                    type="text"
                    placeholder="0"
                    pattern="^\d{0,7}$"
                    title="Please enter a number between 0 and 7 digits">
            </div>
            <div class="range-selector">
                <span class="label">Max</span>
                <span>-</span>
                <span class="rand-symbol">R</span>
                <input
                    id="input-max"
                    type="text"
                    placeholder="any"
                    pattern="^\d{0,7}$"
                    title="Please enter a number between 0 and 7 digits">
            </div>
        </div>
        <svg class="arrow" viewBox="0 0 12 6" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 1L6 5L11 1" stroke="currentColor" stroke-width="1.8" fill="none" />
        </svg>
    </div>

</div>