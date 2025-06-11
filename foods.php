<?php include('partials-front/menu.php'); ?>



    <!-- Live Search CSS -->
    <style>
        /* Live Search Styles */
        .search-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto 30px auto;
        }

        .search-input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 50px;
            outline: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: #fff;
        }

        .search-input:focus {
            border-color: #ff6b6b;
            box-shadow: 0 2px 20px rgba(255,107,107,0.3);
        }

        .search-input::placeholder {
            color: #999;
            font-style: italic;
        }

        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 15px 15px;
            max-height: 350px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
        }

        .search-results::-webkit-scrollbar {
            width: 6px;
        }

        .search-results::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .search-results::-webkit-scrollbar-thumb {
            background: #ff6b6b;
            border-radius: 3px;
        }

        .search-result-item {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-result-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .search-result-item.selected {
            background-color: #e9ecef !important;
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .search-result-content {
            flex: 1;
        }

        .search-result-title {
            font-weight: 600;
            color: #333;
            margin: 0 0 5px 0;
            font-size: 14px;
        }

        .search-result-category {
            font-size: 11px;
            color: #ff6b6b;
            background: linear-gradient(135deg, #ffe6e6, #ffd1d1);
            padding: 3px 10px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 5px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .search-result-price {
            font-weight: 700;
            color: #28a745;
            font-size: 14px;
        }

        .loading-spinner {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            z-index: 10;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #ff6b6b;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .category-badge {
            margin-bottom: 10px;
        }

        .category-tag {
            background: linear-gradient(135deg, #ff6b6b, #e55555);
            color: white;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.7em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            box-shadow: 0 2px 8px rgba(255,107,107,0.3);
        }

        .food-menu-box {
            position: relative;
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .food-menu-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            border-color: #ff6b6b;
        }

        .food-menu-img {
            position: relative;
            overflow: hidden;
            height: 200px;
        }

        .food-menu-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .food-menu-box:hover .food-menu-img img {
            transform: scale(1.05);
        }

        .food-menu-desc {
            padding: 25px;
        }

        .food-menu-desc h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.3em;
            font-weight: 600;
        }

        .food-price {
            font-size: 1.4em;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 10px;
        }

        .food-detail {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff6b6b, #e55555);
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(255,107,107,0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,107,107,0.4);
            background: linear-gradient(135deg, #e55555, #d44444);
        }

        .error {
            background: linear-gradient(135deg, #f8d7da, #f1c2c7);
            color: #721c24;
            padding: 15px 20px;
            border: 1px solid #f5c6cb;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
            background: #f8f9fa;
            border-radius: 15px;
            margin: 20px 0;
        }

        .no-results h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .search-error {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 5px;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>

    <!-- Pass SITEURL to JavaScript -->
    <script>
        window.SITEURL = '<?php echo SITEURL; ?>';
    </script>

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            <!-- <h2>Search Our Delicious Foods</h2> -->
            <!-- <p>Find your favorite dishes quickly and easily</p> -->
            
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search for foods, categories..." class="search-input">
                <div id="searchResults" class="search-results"></div>
                <div id="loadingSpinner" class="loading-spinner" style="display: none;">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->

    <!-- fOOD Menu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>
            <div id="foodMenuContainer">
                <!-- Content will be loaded dynamically -->
                <div class="loading" style="text-align: center; padding: 40px;">
                    <div class="spinner" style="margin: 0 auto;"></div>
                    <p>Loading delicious foods...</p>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
    <!-- fOOD Menu Section Ends Here -->




    
    <!-- JavaScript untuk Live Search -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let searchTimeout;
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const foodMenuContainer = document.getElementById('foodMenuContainer');

            // Check if elements exist
            if (!searchInput || !searchResults || !loadingSpinner || !foodMenuContainer) {
                console.error('Live search elements not found.');
                return;
            }

            // Input event listener
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length === 0) {
                    hideSearchResults();
                    loadAllFoods();
                    return;
                }

                if (query.length < 2) {
                    hideSearchResults();
                    return;
                }

                showLoading();

                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });

            // Focus event
            searchInput.addEventListener('focus', function() {
                if (searchResults.innerHTML.trim() !== '' && this.value.trim().length >= 2) {
                    showSearchResults();
                }
            });

            function performSearch(query) {
                const formData = new FormData();
                formData.append('search', query);

                fetch('ajax-search.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        displaySearchResults(data.results);
                        updateFoodMenu(data.foodMenu);
                    } else {
                        console.error('Search error:', data.message);
                        showError('Search failed. Please try again.');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showError('Connection error. Please check your internet connection.');
                });
            }

            function displaySearchResults(results) {
                if (results.length === 0) {
                    hideSearchResults();
                    return;
                }

                let html = '';
                results.forEach(item => {
                    html += `
                        <div class="search-result-item" onclick="selectFood(${item.id})" data-id="${item.id}">
                            <div class="search-result-content">
                                <div class="search-result-category">${escapeHtml(item.category_name)}</div>
                                <div class="search-result-title">${escapeHtml(item.title)}</div>
                            </div>
                            <div class="search-result-price">$${item.price}</div>
                        </div>
                    `;
                });

                searchResults.innerHTML = html;
                showSearchResults();
            }

            function updateFoodMenu(foodMenuHtml) {
                if (foodMenuContainer) {
                    foodMenuContainer.innerHTML = foodMenuHtml;
                    foodMenuContainer.classList.add('fade-in');
                    
                    setTimeout(() => {
                        foodMenuContainer.classList.remove('fade-in');
                    }, 300);
                }
            }

            function loadAllFoods() {
                const formData = new FormData();
                formData.append('load_all', '1');

                fetch('ajax-search.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateFoodMenu(data.foodMenu);
                    }
                })
                .catch(error => {
                    console.error('Error loading all foods:', error);
                });
            }

            function showSearchResults() {
                searchResults.style.display = 'block';
            }

            function hideSearchResults() {
                searchResults.style.display = 'none';
            }

            function showLoading() {
                loadingSpinner.style.display = 'block';
            }

            function hideLoading() {
                loadingSpinner.style.display = 'none';
            }

            function showError(message) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'search-error';
                errorDiv.textContent = message;
                
                document.body.appendChild(errorDiv);
                
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.parentNode.removeChild(errorDiv);
                    }
                }, 5000);
            }

            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, function(m) { return map[m]; });
            }

            // Hide search results when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.search-container')) {
                    hideSearchResults();
                }
            });

            // Handle keyboard navigation
            searchInput.addEventListener('keydown', function(event) {
                const items = searchResults.querySelectorAll('.search-result-item');
                let selectedIndex = -1;
                
                items.forEach((item, index) => {
                    if (item.classList.contains('selected')) {
                        selectedIndex = index;
                    }
                });

                switch(event.key) {
                    case 'ArrowDown':
                        event.preventDefault();
                        selectedIndex = (selectedIndex + 1) % items.length;
                        updateSelection(items, selectedIndex);
                        break;
                        
                    case 'ArrowUp':
                        event.preventDefault();
                        selectedIndex = selectedIndex <= 0 ? items.length - 1 : selectedIndex - 1;
                        updateSelection(items, selectedIndex);
                        break;
                        
                    case 'Enter':
                        if (selectedIndex >= 0) {
                            event.preventDefault();
                            items[selectedIndex].click();
                        }
                        break;
                        
                    case 'Escape':
                        hideSearchResults();
                        searchInput.blur();
                        break;
                }
            });

            function updateSelection(items, selectedIndex) {
                items.forEach((item, index) => {
                    if (index === selectedIndex) {
                        item.classList.add('selected');
                        item.scrollIntoView({ block: 'nearest' });
                    } else {
                        item.classList.remove('selected');
                    }
                });
            }

            // Global function for selecting food
            window.selectFood = function(foodId) {
                window.location.href = window.SITEURL + 'order.php?food_id=' + foodId;
            };

            // Initialize - load all foods on page load
            loadAllFoods();
        });
    </script>

    <?php include('partials-front/footer.php'); ?>