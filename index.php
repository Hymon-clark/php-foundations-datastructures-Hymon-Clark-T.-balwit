<?php
$file = 'books.json';
if (!file_exists($file)) die("Error: Missing books.json");

$data = json_decode(file_get_contents($file), true);
if (!is_array($data)) die("Error: Invalid JSON format");

function showBooks($id, $title, $books) {
  if (empty($books)) return;

  echo "<section class='category' id='cat_$id'>
          <div class='cat-header'>
            <h2>".htmlspecialchars($title)."</h2>
            <div class='scroll-buttons'>
              <button onclick=\"scrollRow('$id', -1)\"><i>&#10094;</i></button>
              <button onclick=\"scrollRow('$id', 1)\"><i>&#10095;</i></button>
            </div>
          </div>
          <div class='book-list' id='$id'>";

  foreach ($books as $b) {
    $img = htmlspecialchars($b['imageLink'] ?? 'default.jpg');
    $titleTxt = htmlspecialchars($b['title'] ?? 'Untitled');
    $author = htmlspecialchars($b['author'] ?? 'Unknown');
    $year = htmlspecialchars($b['year'] ?? '');

    echo "<div class='book-card' onclick='showPopup(this)' 
            data-title='$titleTxt' data-author='$author' 
            data-year='$year' data-img='images/$img'>
            <div class='book-cover'>
              <img src='images/$img' alt='$titleTxt'>
            </div>
            <div class='book-info'>
              <h3>$titleTxt</h3>
              <p>$author".($year ? " • $year" : "")."</p>
            </div>
          </div>";
  }

  echo "</div></section>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Collection Library</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap');

* { box-sizing: border-box; }

body {
  margin: 0;
  font-family: 'Outfit', sans-serif;
  color: #f4eaff;
  background: radial-gradient(circle at top left, #180020, #120019, #0a0010);
  background-attachment: fixed;
  overflow-x: hidden;
  min-height: 100vh;
}

body::before {
  content: "";
  position: fixed;
  inset: 0;
  background: linear-gradient(115deg, rgba(255,0,255,0.25), rgba(0,255,255,0.15), rgba(140,0,255,0.2));
  filter: blur(120px);
  opacity: 0.7;
  z-index: -1;
}

header {
  text-align: center;
  padding: 70px 20px 30px;
}
header h1 {
  font-size: 2.8em;
  color: #c387ff;
  text-shadow: 0 0 25px #c387ffb3, 0 0 50px #7b00ff80;
  letter-spacing: 3px;
}

.search-bar {
  display: flex;
  justify-content: center;
  margin-bottom: 60px;
}
.search-bar input {
  width: 60%;
  max-width: 480px;
  padding: 14px 22px;
  border: none;
  border-radius: 50px;
  background: rgba(255,255,255,0.1);
  color: #fff;
  font-size: 16px;
  backdrop-filter: blur(12px);
  box-shadow: 0 0 15px rgba(200,100,255,0.2);
  transition: 0.3s ease;
  outline: none;
}
.search-bar input:focus {
  background: rgba(160,0,255,0.2);
  box-shadow: 0 0 25px #b966ff;
}

.category {
  padding: 40px 50px 70px;
  position: relative;
}
.cat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 18px;
}
.cat-header h2 {
  font-size: 1.8em;
  color: #e2c6ff;
  text-shadow: 0 0 12px #c387ffb3;
}
.scroll-buttons button {
  background: rgba(255,255,255,0.1);
  border: none;
  color: #fff;
  font-size: 24px;
  border-radius: 50%;
  width: 44px;
  height: 44px;
  margin: 0 5px;
  cursor: pointer;
  transition: 0.3s ease;
}
.scroll-buttons button:hover {
  background: linear-gradient(135deg, #c387ff, #8af5ff);
  color: #000;
  box-shadow: 0 0 15px #c387ff;
}

.book-list {
  display: flex;
  gap: 28px;
  overflow-x: auto;
  scroll-behavior: smooth;
  padding-bottom: 15px;
}
.book-list::-webkit-scrollbar { display: none; }

.book-card {
  flex: 0 0 auto;
  width: 200px;
  background: rgba(255,255,255,0.08);
  border-radius: 20px;
  overflow: hidden;
  cursor: pointer;
  box-shadow: 0 6px 25px rgba(0,0,0,0.6);
  transition: 0.35s ease;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(200,150,255,0.15);
}
.book-card:hover {
  transform: translateY(-10px) scale(1.05);
  box-shadow: 0 0 25px #b966ffb0;
  border-color: rgba(200,150,255,0.4);
}
.book-cover img {
  width: 100%;
  height: 270px;
  object-fit: cover;
  border-bottom: 1px solid rgba(255,255,255,0.15);
}
.book-info {
  text-align: center;
  padding: 12px;
}
.book-info h3 {
  font-size: 16px;
  color: #fff;
  margin: 6px 0;
  height: 40px;
  overflow: hidden;
}
.book-info p {
  color: #ccc;
  font-size: 13px;
}

.popup {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(15,0,25,0.9);
  justify-content: center;
  align-items: center;
  z-index: 100;
}
.popup.active { display: flex; }

.popup-content {
  background: rgba(255,255,255,0.08);
  padding: 28px;
  border-radius: 22px;
  width: 360px;
  text-align: center;
  color: #fff;
  backdrop-filter: blur(16px);
  box-shadow: 0 0 40px rgba(200,100,255,0.6);
  border: 1px solid rgba(200,150,255,0.3);
  animation: popupFade 0.4s ease;
}
.popup-content img {
  width: 100%;
  border-radius: 12px;
  margin-bottom: 15px;
  box-shadow: 0 0 20px rgba(150,50,255,0.4);
}
.popup-content h3 {
  color: #c387ff;
  font-size: 20px;
  margin-bottom: 10px;
}
.popup-content p {
  color: #e5d8ff;
  margin: 4px 0;
}

.close-btn {
  position: absolute;
  top: 20px; right: 25px;
  background: linear-gradient(135deg, #b966ff, #ff77ff);
  border: none;
  color: #fff;
  width: 36px; height: 36px;
  border-radius: 50%;
  font-size: 18px;
  cursor: pointer;
  box-shadow: 0 0 10px #b966ff;
  transition: 0.3s;
}
.close-btn:hover {
  background: linear-gradient(135deg, #ff99ff, #c387ff);
  box-shadow: 0 0 15px #ffccff;
}

@keyframes popupFade {
  from { opacity: 0; transform: scale(0.85); }
  to { opacity: 1; transform: scale(1); }
}

@media (max-width: 700px) {
  .book-card { width: 160px; }
  .book-cover img { height: 220px; }
  .search-bar input { width: 80%; }
  header h1 { font-size: 2.3em; }
}
</style>
</head>
<body>

<header>
  <h1> BALWIT COLLECTION LIBRARY </h1>
</header>

<div class="search-bar">
  <input type="text" id="searchInput" placeholder="Search books...">
</div>

<?php
  showBooks("history", "History Books", $data["history_books"] ?? []);
  showBooks("adventure", "Adventure Books", $data["adventure_books"] ?? []);
  showBooks("action", "Action Books", $data["action_books"] ?? []);
?>

<div class="popup" id="bookPopup">
  <div class="popup-content">
    <button class="close-btn" onclick="closePopup()">×</button>
    <img id="popupImg" src="">
    <h3 id="popupTitle"></h3>
    <p id="popupAuthor"></p>
    <p id="popupYear"></p>
  </div>
</div>

<script>
function scrollRow(id, dir) {
  document.getElementById(id).scrollBy({ left: dir * 280, behavior: 'smooth' });
}

function showPopup(card) {
  document.getElementById("bookPopup").classList.add("active");
  document.getElementById("popupImg").src = card.dataset.img;
  document.getElementById("popupTitle").innerText = card.dataset.title;
  document.getElementById("popupAuthor").innerText = "By " + card.dataset.author;
  document.getElementById("popupYear").innerText = "Published: " + card.dataset.year;
}
function closePopup() {
  document.getElementById("bookPopup").classList.remove("active");
}

document.getElementById("searchInput").addEventListener("input", function() {
  let query = this.value.toLowerCase();
  document.querySelectorAll(".book-card").forEach(c => {
    let text = (c.dataset.title + c.dataset.author).toLowerCase();
    c.style.display = text.includes(query) ? "block" : "none";
  });
});
</script>
</body>
</html>
