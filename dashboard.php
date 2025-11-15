<?php
session_start();

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'], $_SESSION['user_type'], $_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username  = $_SESSION['username'];
$user_type = $_SESSION['user_type']; // 'shopkeeper' or 'customer'
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* ---------- Fonts ---------- */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

:root{
  --bg-1: #e9f7ef;
  --accent-green: #2fb65d;
  --accent-green-dark: #238a47;
  --accent-blue: #2f9fd6;
  --card-bg: rgba(255,255,255,0.97);
  --muted: #6b7280;
  --glass-shadow: 0 12px 30px rgba(15, 23, 42, 0.18);
}

/* ---------- Reset ---------- */
* { box-sizing: border-box; margin:0; padding:0; }
html,body { height:100%; }

/* ---------- Body + Background (inline SVG + gradient) ---------- */
body {
  font-family: 'Poppins', Arial, sans-serif;
  background:
    linear-gradient(180deg, rgba(255,255,255,0.55), rgba(240,255,248,0.55)),
    url("data:image/svg+xml;utf8,<?xml version='1.0' encoding='UTF-8'?> \
    <svg xmlns='http://www.w3.org/2000/svg' width='1600' height='900' viewBox='0 0 1600 900'> \
      <defs> \
        <linearGradient id='g1' x1='0' x2='1' y1='0' y2='1'> \
          <stop offset='0' stop-color='%23f7fff4'/> \
          <stop offset='1' stop-color='%23e7fcf0'/> \
        </linearGradient> \
      </defs> \
      <rect width='100%' height='100%' fill='url(%23g1)'/> \
      <!-- stylized produce silhouettes for pattern --> \
      <g opacity='0.06' fill='%23000000'> \
        <ellipse cx='120' cy='130' rx='60' ry='36' /> \
        <ellipse cx='290' cy='80' rx='80' ry='46' /> \
        <ellipse cx='700' cy='40' rx='100' ry='58' /> \
        <ellipse cx='1200' cy='180' rx='70' ry='40' /> \
        <ellipse cx='1400' cy='80' rx='90' ry='52' /> \
      </g> \
      <!-- subtle leaves pattern --> \
      <g fill='%23000000' opacity='0.03'> \
        <path d='M1400 700c-30 10-50 30-70 20s-30-60 40-90 70-10 80 10-5 50-50 60z'/> \
        <path d='M200 780c20-20 60-10 70 10s-20 60-70 50-40-50 0-80z'/> \
      </g> \
    </svg>") no-repeat center/cover;
  min-height:100vh;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
  color: #0b2b17;
}

/* overlay to dim background for readability */
.site-overlay {
  background: rgba(5, 35, 20, 0.06);
  min-height: 100vh;
  padding: 40px 20px;
  display:flex;
  align-items:center;
  justify-content:center;
}

/* ---------- Main card ---------- */
.container {
  width: 100%;
  max-width: 980px;
  border-radius: 20px;
  background: var(--card-bg);
  box-shadow: var(--glass-shadow);
  padding: 36px;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(34,197,94,0.06);
}

/* decorative soft blobs */
.container::before,
.container::after {
  content: '';
  position: absolute;
  width: 320px;
  height: 320px;
  border-radius: 50%;
  filter: blur(46px);
  opacity: 0.65;
  transform: translateZ(0);
  z-index:0;
}
.container::before{
  right: -80px;
  top: -90px;
  background: linear-gradient(135deg, rgba(47,159,214,0.18), rgba(47,182,93,0.14));
}
.container::after{
  left: -120px;
  bottom: -100px;
  background: linear-gradient(135deg, rgba(47,182,93,0.14), rgba(47,159,214,0.12));
}

/* ---------- Header ---------- */
.header {
  position: relative;
  z-index: 2;
  display:flex;
  align-items:center;
  gap:18px;
  justify-content:center;
  flex-direction:column;
  padding-bottom: 8px;
  border-bottom: 1px dashed rgba(15, 23, 42, 0.05);
}

.brand {
  display:flex;
  align-items:center;
  gap:12px;
}
.logo {
  width:58px;
  height:58px;
  border-radius:12px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  font-size:26px;
  background: linear-gradient(135deg,#2fb65d,#2f9fd6);
  color:white;
  box-shadow: 0 6px 18px rgba(47,159,214,0.18);
}
h2 { font-size:28px; font-weight:700; color: #0c3d24; margin-bottom:6px; }
.welcome-sub { color: var(--muted); font-size:15px; }

/* small stats row */
.stats {
  margin-top:18px;
  display:flex;
  gap:12px;
  justify-content:center;
  flex-wrap:wrap;
  z-index:2;
}
.stat {
  background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,255,250,0.98));
  border-radius:12px;
  padding:12px 18px;
  min-width:140px;
  box-shadow: 0 8px 18px rgba(10,20,10,0.04);
  text-align:center;
}
.stat .num { font-weight:700; color:#123b26; font-size:18px; }
.stat .label { font-size:12px; color:var(--muted); margin-top:4px; }

/* ---------- Buttons area ---------- */
.controls {
  margin-top: 26px;
  display:flex;
  gap:18px;
  justify-content:center;
  align-items:center;
  z-index:2;
  flex-wrap:wrap;
}

/* large pill buttons */
.pill {
  display:inline-flex;
  gap:10px;
  align-items:center;
  padding:18px 28px;
  border-radius:14px;
  color:white;
  text-decoration:none;
  font-weight:700;
  font-size:16px;
  box-shadow: 0 8px 22px rgba(10,40,20,0.08);
  transform: translateY(0);
  transition: transform .28s cubic-bezier(.2,.9,.3,1), box-shadow .28s, opacity .28s;
}

/* icons as circle badges */
.pill .ico {
  width:36px;
  height:36px;
  border-radius:10px;
  background: rgba(255,255,255,0.12);
  display:inline-flex;
  align-items:center;
  justify-content:center;
  font-size:18px;
}

/* green theme (shopkeeper) */
.pill.green {
  background: linear-gradient(135deg, var(--accent-green), var(--accent-green-dark));
}
.pill.green:hover{ transform:translateY(-7px); box-shadow: 0 18px 50px rgba(47,182,93,0.18); }

/* blue theme (customer) */
.pill.blue {
  background: linear-gradient(135deg, var(--accent-blue), #186aa6);
}
.pill.blue:hover{ transform:translateY(-7px); box-shadow: 0 18px 50px rgba(47,159,214,0.14); }

/* tertiary pale buttons */
.pill.ghost {
  background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(245,255,250,0.9));
  color: #0c5538;
  border: 1px solid rgba(15,23,42,0.04);
  box-shadow: 0 8px 18px rgba(10,20,10,0.03);
}
.pill.ghost:hover{ transform:translateY(-4px); }

/* ---------- Grid for three big actions ---------- */
.actions {
  margin-top: 28px;
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap:18px;
  z-index:2;
}
@media (max-width:820px){ .actions { grid-template-columns: 1fr; } }

/* action cards */
.action-card{
  border-radius:14px;
  padding:22px;
  background: linear-gradient(180deg, rgba(255,255,255,0.99), rgba(250,255,250,0.99));
  box-shadow: 0 10px 28px rgba(8,30,10,0.04);
  display:flex;
  align-items:center;
  gap:14px;
  transition: transform .28s, box-shadow .28s;
}
.action-card:hover { transform: translateY(-8px); box-shadow: 0 30px 60px rgba(8,30,10,0.08); }
.action-card .icon {
  width:62px;
  height:62px;
  border-radius:12px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:26px;
  color:white;
}
.action-card .meta .title { font-size:16px; font-weight:700; color:#0f3b22; }
.action-card .meta .desc { font-size:13px; color:var(--muted); margin-top:6px; }

/* icon backgrounds */
.icon.green { background: linear-gradient(135deg,var(--accent-green),var(--accent-green-dark)); box-shadow:0 8px 20px rgba(47,182,93,0.12); }
.icon.blue { background: linear-gradient(135deg,var(--accent-blue),#186aa6); box-shadow:0 8px 20px rgba(47,159,214,0.12); }
.icon.orange { background: linear-gradient(135deg,#ff9a3c,#ff6b2f); box-shadow:0 8px 20px rgba(255,110,50,0.12); }

/* footer logout aligned center */
.form-logout { margin-top: 26px; text-align:center; z-index:2; }

/* plain logout */
button.logout {
  background: linear-gradient(135deg,#ef4444,#d32f2f);
  padding:10px 18px;
  color:white;
  border-radius:10px;
  font-weight:700;
  border: none;
  cursor: pointer;
  box-shadow: 0 10px 26px rgba(220,60,60,0.16);
  transition: transform .22s, box-shadow .22s;
}
button.logout:hover { transform:translateY(-4px); box-shadow: 0 18px 40px rgba(220,60,60,0.18); }

/* small helper */
.muted { color:var(--muted); font-size:13px; }

/* keep z-index high for content */
.container > * { position: relative; z-index: 2; }

</style>
</head>
<body>

<div class="site-overlay">
  <div class="container">

    <div class="header">
      <div class="brand">
        <div class="logo" aria-hidden>🍏</div>
        <div style="text-align:left">
          <h2>Welcome, <?= htmlspecialchars($username); ?> 👋</h2>
          <div class="welcome-sub">You are logged in as <strong><?= htmlspecialchars($user_type); ?></strong></div>
        </div>
      </div>

      <div class="stats" role="list">
        <!-- These are decorative placeholders. If you want live counts we can wire them to DB. -->
        <div class="stat" role="listitem">
          <div class="num">—</div>
          <div class="label">Active Products</div>
        </div>
        <div class="stat" role="listitem">
          <div class="num">—</div>
          <div class="label">Pending Orders</div>
        </div>
        <div class="stat" role="listitem">
          <div class="num">—</div>
          <div class="label">My Earnings</div>
        </div>
      </div>
    </div>

    <!-- Top quick actions -->
    <div class="controls">
      <?php if ($user_type === 'shopkeeper'): ?>
        <a class="pill green" href="shopkeeper/manage_products.php"><span class="ico">📦</span> Manage Products</a>
        <a class="pill green" href="shopkeeper/add_product.php"><span class="ico">➕</span> Add Product</a>
        <a class="pill green" href="shopkeeper/orders.php"><span class="ico">📑</span> View Orders</a>
      <?php elseif ($user_type === 'customer'): ?>
        <a class="pill blue" href="customer/products.php"><span class="ico">🍉</span> Browse Products</a>
        <a class="pill blue" href="customer/view_cart.php"><span class="ico">🛒</span> View Cart</a>
        <a class="pill blue" href="orders/list_orders.php"><span class="ico">📦</span> My Orders</a>
      <?php endif; ?>
    </div>

    <!-- Big action cards (grocery friendly visuals) -->
    <div class="actions">
      <?php if ($user_type === 'shopkeeper'): ?>
        <a class="action-card" href="shopkeeper/manage_products.php">
          <div class="icon green">📦</div>
          <div class="meta">
            <div class="title">Manage your catalog</div>
            <div class="desc muted">Edit stock, prices and images quickly.</div>
          </div>
        </a>

        <a class="action-card" href="shopkeeper/add_product.php">
          <div class="icon orange">➕</div>
          <div class="meta">
            <div class="title">Add a new product</div>
            <div class="desc muted">Add fresh produce or packaged goods.</div>
          </div>
        </a>

        <a class="action-card" href="shopkeeper/orders.php">
          <div class="icon blue">📑</div>
          <div class="meta">
            <div class="title">View & manage orders</div>
            <div class="desc muted">Confirm deliveries, update statuses.</div>
          </div>
        </a>

      <?php elseif ($user_type === 'customer'): ?>
        <a class="action-card" href="customer/products.php">
          <div class="icon blue">🍉</div>
          <div class="meta">
            <div class="title">Explore fresh items</div>
            <div class="desc muted">Fruits, vegetables & daily essentials.</div>
          </div>
        </a>

        <a class="action-card" href="customer/view_cart.php">
          <div class="icon green">🛒</div>
          <div class="meta">
            <div class="title">Your shopping cart</div>
            <div class="desc muted">Review items before checkout.</div>
          </div>
        </a>

        <a class="action-card" href="orders/list_orders.php">
          <div class="icon orange">📦</div>
          <div class="meta">
            <div class="title">Order history</div>
            <div class="desc muted">Track past orders and receipts.</div>
          </div>
        </a>
      <?php endif; ?>
    </div>

    <div class="form-logout">
      <form action="logout.php" method="POST" style="display:inline-block;">
        <button type="submit" class="logout">Logout</button>
      </form>
    </div>

  </div>
</div>

</body>
</html>
