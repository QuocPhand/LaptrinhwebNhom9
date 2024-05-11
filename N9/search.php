<link rel="stylesheet" href="css/search.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Hero Section -->
<div class="search" id="search_section">
    <h2>Bạn Muốn Tìm Món Gì?</h2>
    <form id="search-form" method="get" action="search_results.php">
        <input type="text" name="query" placeholder="Search..." aria-label="Search" required>
        <button type="submit"><img src="images/search.png" alt="search_icon"></button>
    </form>

    <!-- Display Search Results -->
    <div class="search-results" id="search-results">
        <img src="images/Search here.jpg" alt="Tìm Kiếm" class="searching-img">
    </div>
</div>

<script src="js/search.js"></script>
