<!doctype html>
<html lang="id">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>INVS STUDIO CUSTOM</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link
		href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
		rel="stylesheet" />

	<script src="<?php echo base_url(); ?>assets/js/v_home.js"></script>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/v_home.css">
</head>

<body class="min-h-screen">
	<!-- ===================== NAVBAR ===================== -->
	<nav
		class="fixed top-0 left-0 right-0 z-50 px-6 py-4 flex items-center justify-between">
		<div class="flex items-center gap-3">
			<svg width="28" height="28" viewBox="0 0 28 28" fill="none">
				<polygon
					points="14,2 26,9 26,19 14,26 2,19 2,9"
					stroke="#d4a017"
					stroke-width="1.5"
					fill="none" />
				<polygon
					points="14,6 22,11 22,17 14,22 6,17 6,11"
					stroke="#d4a017"
					stroke-width="0.5"
					fill="rgba(212,160,23,0.05)" />
			</svg>
			<span
				class="font-title font-800 text-base tracking-widest text-gold-400 uppercase"
				style="
						font-family: &quot;Syne&quot;, sans-serif;
						font-weight: 800;
						letter-spacing: 0.2em;
					">INVS</span>
		</div>
		<div class="hidden md:flex items-center gap-8">
			<a
				href="#catalog"
				class="text-xs font-title uppercase tracking-widest text-smoke hover:text-gold-400 transition-colors"
				style="
						font-family: &quot;Syne&quot;, sans-serif;
						letter-spacing: 0.15em;
					">Katalog</a>
			<a
				href="#process"
				class="text-xs font-title uppercase tracking-widest text-smoke hover:text-gold-400 transition-colors"
				style="
						font-family: &quot;Syne&quot;, sans-serif;
						letter-spacing: 0.15em;
					">Proses</a>
			<a
				href="#order"
				class="text-xs font-title uppercase tracking-widest text-smoke hover:text-gold-400 transition-colors"
				style="
						font-family: &quot;Syne&quot;, sans-serif;
						letter-spacing: 0.15em;
					">Order</a>
		</div>
		<a href="#order" class="btn-gold text-xs py-3 px-6 hidden md:flex">Pesan Sekarang</a>
		<button class="md:hidden text-gold-400" onclick="toggleMobileMenu()">
			<svg
				width="22"
				height="22"
				fill="none"
				stroke="currentColor"
				stroke-width="1.5"
				viewBox="0 0 24 24">
				<path d="M4 6h16M4 12h16M4 18h16" />
			</svg>
		</button>
	</nav>

	<!-- Mobile menu -->
	<div
		id="mobile-menu"
		class="fixed inset-0 z-40 bg-onyx hidden flex-col items-center justify-center gap-8">
		<button
			class="absolute top-5 right-6 text-gold-400"
			onclick="toggleMobileMenu()">
			<svg
				width="24"
				height="24"
				fill="none"
				stroke="currentColor"
				stroke-width="1.5"
				viewBox="0 0 24 24">
				<path d="M6 18L18 6M6 6l12 12" />
			</svg>
		</button>
		<a
			href="#catalog"
			onclick="toggleMobileMenu()"
			class="text-2xl font-title uppercase tracking-widest text-gold-400"
			style="
					font-family: &quot;Cormorant Garamond&quot;, serif;
					font-size: 2rem;
				">Katalog</a>
		<a
			href="#process"
			onclick="toggleMobileMenu()"
			class="text-2xl font-title uppercase tracking-widest text-gold-400"
			style="
					font-family: &quot;Cormorant Garamond&quot;, serif;
					font-size: 2rem;
				">Proses</a>
		<a
			href="#order"
			onclick="toggleMobileMenu()"
			class="text-2xl font-title uppercase tracking-widest text-gold-400"
			style="
					font-family: &quot;Cormorant Garamond&quot;, serif;
					font-size: 2rem;
				">Order</a>
		<a href="#order" onclick="toggleMobileMenu()" class="btn-gold mt-4">Pesan Sekarang →</a>
	</div>

	<!-- ===================== HERO ===================== -->
	<section
		class="hero-bg min-h-screen flex flex-col items-center justify-center text-center px-6 pt-20 relative overflow-hidden">
		<!-- BG decorative rings -->
		<div
			class="absolute inset-0 flex items-center justify-center pointer-events-none">
			<div
				class="rounded-full border border-gold-600 opacity-5"
				style="width: 600px; height: 600px"></div>
			<div
				class="absolute rounded-full border border-gold-600 opacity-5"
				style="width: 900px; height: 900px"></div>
		</div>

		<div class="relative z-10 max-w-4xl mx-auto">
			<p
				class="text-xs uppercase tracking-widest text-gold-500 mb-6 fade-up"
				style="
						font-family: &quot;Syne&quot;, sans-serif;
						letter-spacing: 0.3em;
					">
				Premium Custom Apparel · Est. 2019
			</p>

			<h1
				class="fade-up delay-1 mb-6"
				style="
						font-family: &quot;Cormorant Garamond&quot;, serif;
						font-size: clamp(3rem, 8vw, 6.5rem);
						font-weight: 300;
						line-height: 1.05;
						color: #e8e8e0;
					">
				Pakaian <em style="font-style: italic" class="gold-text">Custom</em><br />Sesuai Keinginanmu
			</h1>

			<p
				class="text-smoke text-base md:text-lg max-w-xl mx-auto mb-10 fade-up delay-2"
				style="line-height: 1.7">
				Dari desain pribadimu menjadi produk nyata. T-shirt, hoodie, polo
				shirt — dikerjakan dengan presisi dan bahan premium.
			</p>

			<div
				class="flex flex-col sm:flex-row gap-4 justify-center fade-up delay-3">
				<a href="#order" class="btn-gold">
					Pesan Sekarang
					<svg
						width="16"
						height="16"
						fill="none"
						stroke="currentColor"
						stroke-width="2"
						viewBox="0 0 24 24">
						<path d="M5 12h14M12 5l7 7-7 7" />
					</svg>
				</a>
				<a href="#catalog" class="btn-outline-gold">Lihat Katalog</a>
			</div>

			<!-- Stats row -->
			<div
				class="mt-20 grid grid-cols-3 gap-6 max-w-lg mx-auto fade-up delay-3">
				<div class="text-center">
					<div
						class="gold-text text-3xl font-display font-semibold"
						style="
								font-family: &quot;Cormorant Garamond&quot;, serif;
								font-size: 2.5rem;
							">
						500+
					</div>
					<div
						class="text-xs text-smoke uppercase tracking-widest mt-1"
						style="font-family: &quot;Syne&quot;, sans-serif">
						Order Selesai
					</div>
				</div>
				<div
					class="text-center border-x border-gold"
					style="border-color: rgba(212, 160, 23, 0.15)">
					<div
						class="gold-text text-3xl font-display font-semibold"
						style="
								font-family: &quot;Cormorant Garamond&quot;, serif;
								font-size: 2.5rem;
							">
						5+
					</div>
					<div
						class="text-xs text-smoke uppercase tracking-widest mt-1"
						style="font-family: &quot;Syne&quot;, sans-serif">
						Tahun Berdiri
					</div>
				</div>
				<div class="text-center">
					<div
						class="gold-text text-3xl font-display font-semibold"
						style="
								font-family: &quot;Cormorant Garamond&quot;, serif;
								font-size: 2.5rem;
							">
						48h
					</div>
					<div
						class="text-xs text-smoke uppercase tracking-widest mt-1"
						style="font-family: &quot;Syne&quot;, sans-serif">
						Produksi Cepat
					</div>
				</div>
			</div>
		</div>

		<!-- Scroll indicator -->
		<div
			class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-40">
			<span
				class="text-xs uppercase tracking-widest text-smoke"
				style="font-family: &quot;Syne&quot;, sans-serif; font-size: 9px">Scroll</span>
			<div
				style="
						width: 1px;
						height: 40px;
						background: linear-gradient(
							to bottom,
							rgba(212, 160, 23, 0.6),
							transparent
						);
					"></div>
		</div>
	</section>

	<!-- ===================== MARQUEE ===================== -->
	<!-- <div
		class="overflow-hidden py-4 border-y border-gold"
		style="
				border-color: rgba(212, 160, 23, 0.12);
				background: rgba(212, 160, 23, 0.02);
			">
		<div class="marquee-track">
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">T-Shirt Custom</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">Hoodie Premium</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">Polo Shirt</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">Tote Bag</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">DTF Print</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">Screen Print</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">T-Shirt Custom</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">Hoodie Premium</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">Polo Shirt</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">Tote Bag</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">DTF Print</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
			<span
				class="text-xs uppercase tracking-widest text-gold-600 mx-8"
				style="font-family: &quot;Syne&quot;, sans-serif; opacity: 0.5">Screen Print</span>
			<span class="text-gold-500 mx-4" style="opacity: 0.3">✦</span>
		</div>
	</div> -->

	<!-- ===================== CATALOG ===================== -->
	<section id="catalog" class="py-24 px-6">
		<div class="max-w-6xl mx-auto">
			<!-- Section header -->
			<div class="text-center mb-16">
				<p
					class="text-xs uppercase tracking-widest text-gold-500 mb-4"
					style="
							font-family: &quot;Syne&quot;, sans-serif;
							letter-spacing: 0.3em;
						">
					Pilihan Produk
				</p>
				<h2
					class="gold-text mb-4"
					style="
							font-family: &quot;Cormorant Garamond&quot;, serif;
							font-size: clamp(2rem, 5vw, 3.5rem);
							font-weight: 300;
						">
					Koleksi Kami
				</h2>
				<div class="deco-line mx-auto"></div>
			</div>

			<!-- Product grid -->
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				<!-- T-Shirt DTF -->
				<div class="product-card">
					<div class="product-img">
						<svg
							width="100"
							height="120"
							viewBox="0 0 100 120"
							fill="none"
							xmlns="http://www.w3.org/2000/svg"
							style="opacity: 0.25">
							<path
								d="M30 10 L10 30 L22 36 L22 100 L78 100 L78 36 L90 30 L70 10 L60 18 C57 22 43 22 40 18 Z"
								stroke="#d4a017"
								stroke-width="1.5"
								fill="rgba(212,160,23,0.05)" />
							<path
								d="M38 50 C42 58 58 58 62 50"
								stroke="#d4a017"
								stroke-width="1"
								stroke-dasharray="3 2" />
						</svg>
						<div
							class="absolute top-3 right-3 bg-gold-500 text-onyx text-xs px-2 py-1"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									font-weight: 700;
									letter-spacing: 0.1em;
								">
							BESTSELLER
						</div>
					</div>
					<div class="p-6">
						<p
							class="text-xs uppercase tracking-widest text-smoke mb-1"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									letter-spacing: 0.15em;
								">
							T-Shirt
						</p>
						<h3
							class="text-white mb-2"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-weight: 700;
									font-size: 1rem;
								">
							DTF Print Custom
						</h3>
						<p class="text-smoke text-sm mb-4" style="line-height: 1.6">
							Cetak penuh warna dengan teknologi DTF. Hasil detail, tahan
							lama, cocok untuk desain kompleks.
						</p>
						<div class="flex items-center justify-between">
							<div>
								<span class="text-xs text-smoke">Mulai dari</span>
								<div
									class="gold-text"
									style="
											font-family: &quot;Cormorant Garamond&quot;, serif;
											font-size: 1.4rem;
											font-weight: 600;
										">
									Rp 75.000<span class="text-sm text-smoke font-sans">/pcs</span>
								</div>
							</div>
							<a href="#order" class="btn-outline-gold py-2 px-4 text-xs">Pesan</a>
						</div>
					</div>
				</div>

				<!-- Hoodie -->
				<div class="product-card">
					<div class="product-img">
						<svg
							width="100"
							height="130"
							viewBox="0 0 100 130"
							fill="none"
							xmlns="http://www.w3.org/2000/svg"
							style="opacity: 0.25">
							<path
								d="M40 8 C40 8 36 14 32 16 L10 28 L18 40 L24 36 L24 120 L76 120 L76 36 L82 40 L90 28 L68 16 C64 14 60 8 60 8 L52 12 C50 14 50 14 48 12 Z"
								stroke="#d4a017"
								stroke-width="1.5"
								fill="rgba(212,160,23,0.05)" />
							<ellipse
								cx="50"
								cy="10"
								rx="8"
								ry="5"
								stroke="#d4a017"
								stroke-width="1.2"
								fill="none" />
							<path
								d="M30 60 L70 60"
								stroke="#d4a017"
								stroke-width="0.8"
								stroke-dasharray="4 3" />
						</svg>
						<div
							class="absolute top-3 right-3 bg-graphite border border-gold text-gold-400 text-xs px-2 py-1"
							style="
									border-color: rgba(212, 160, 23, 0.3);
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									font-weight: 700;
									letter-spacing: 0.1em;
								">
							PREMIUM
						</div>
					</div>
					<div class="p-6">
						<p
							class="text-xs uppercase tracking-widest text-smoke mb-1"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									letter-spacing: 0.15em;
								">
							Hoodie
						</p>
						<h3
							class="text-white mb-2"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-weight: 700;
									font-size: 1rem;
								">
							Hoodie Fleece
						</h3>
						<p class="text-smoke text-sm mb-4" style="line-height: 1.6">
							Bahan fleece premium, hangat dan nyaman. Tersedia sablon dan
							bordir sesuai kebutuhan.
						</p>
						<div class="flex items-center justify-between">
							<div>
								<span class="text-xs text-smoke">Mulai dari</span>
								<div
									class="gold-text"
									style="
											font-family: &quot;Cormorant Garamond&quot;, serif;
											font-size: 1.4rem;
											font-weight: 600;
										">
									Rp 185.000<span class="text-sm text-smoke font-sans">/pcs</span>
								</div>
							</div>
							<a href="#order" class="btn-outline-gold py-2 px-4 text-xs">Pesan</a>
						</div>
					</div>
				</div>

				<!-- Polo Shirt -->
				<div class="product-card">
					<div class="product-img">
						<svg
							width="100"
							height="120"
							viewBox="0 0 100 120"
							fill="none"
							xmlns="http://www.w3.org/2000/svg"
							style="opacity: 0.25">
							<path
								d="M30 10 L10 28 L22 35 L22 100 L78 100 L78 35 L90 28 L70 10 L62 16 C58 20 54 22 50 22 C46 22 42 20 38 16 Z"
								stroke="#d4a017"
								stroke-width="1.5"
								fill="rgba(212,160,23,0.05)" />
							<path
								d="M44 10 L44 28 M56 10 L56 28"
								stroke="#d4a017"
								stroke-width="1"
								stroke-dasharray="2 2" />
							<circle cx="50" cy="14" r="1.5" fill="#d4a017" opacity="0.5" />
							<circle cx="50" cy="20" r="1.5" fill="#d4a017" opacity="0.5" />
						</svg>
					</div>
					<div class="p-6">
						<p
							class="text-xs uppercase tracking-widest text-smoke mb-1"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									letter-spacing: 0.15em;
								">
							Polo Shirt
						</p>
						<h3
							class="text-white mb-2"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-weight: 700;
									font-size: 1rem;
								">
							Polo Lacoste Custom
						</h3>
						<p class="text-smoke text-sm mb-4" style="line-height: 1.6">
							Cocok untuk seragam perusahaan, komunitas, dan event. Bordir
							logo tersedia.
						</p>
						<div class="flex items-center justify-between">
							<div>
								<span class="text-xs text-smoke">Mulai dari</span>
								<div
									class="gold-text"
									style="
											font-family: &quot;Cormorant Garamond&quot;, serif;
											font-size: 1.4rem;
											font-weight: 600;
										">
									Rp 110.000<span class="text-sm text-smoke font-sans">/pcs</span>
								</div>
							</div>
							<a href="#order" class="btn-outline-gold py-2 px-4 text-xs">Pesan</a>
						</div>
					</div>
				</div>

				<!-- Screen Print -->
				<div class="product-card">
					<div class="product-img">
						<svg
							width="110"
							height="80"
							viewBox="0 0 110 80"
							fill="none"
							style="opacity: 0.2">
							<rect
								x="10"
								y="10"
								width="90"
								height="60"
								rx="2"
								stroke="#d4a017"
								stroke-width="1.5"
								fill="rgba(212,160,23,0.04)" />
							<path
								d="M25 30 L50 50 L85 20"
								stroke="#d4a017"
								stroke-width="2"
								stroke-linecap="round" />
							<circle
								cx="50"
								cy="40"
								r="20"
								stroke="#d4a017"
								stroke-width="0.8"
								stroke-dasharray="4 3" />
						</svg>
					</div>
					<div class="p-6">
						<p
							class="text-xs uppercase tracking-widest text-smoke mb-1"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									letter-spacing: 0.15em;
								">
							T-Shirt
						</p>
						<h3
							class="text-white mb-2"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-weight: 700;
									font-size: 1rem;
								">
							Screen Print Classic
						</h3>
						<p class="text-smoke text-sm mb-4" style="line-height: 1.6">
							Sablon manual berkualitas tinggi. Terbaik untuk desain 1-4 warna
							dalam jumlah banyak.
						</p>
						<div class="flex items-center justify-between">
							<div>
								<span class="text-xs text-smoke">Mulai dari</span>
								<div
									class="gold-text"
									style="
											font-family: &quot;Cormorant Garamond&quot;, serif;
											font-size: 1.4rem;
											font-weight: 600;
										">
									Rp 65.000<span class="text-sm text-smoke font-sans">/pcs</span>
								</div>
							</div>
							<a href="#order" class="btn-outline-gold py-2 px-4 text-xs">Pesan</a>
						</div>
					</div>
				</div>

				<!-- Tote Bag -->
				<div class="product-card">
					<div class="product-img">
						<svg
							width="90"
							height="110"
							viewBox="0 0 90 110"
							fill="none"
							style="opacity: 0.22">
							<path
								d="M30 30 L15 100 L75 100 L60 30 Z"
								stroke="#d4a017"
								stroke-width="1.5"
								fill="rgba(212,160,23,0.05)" />
							<path
								d="M30 30 C30 20 35 12 45 12 C55 12 60 20 60 30"
								stroke="#d4a017"
								stroke-width="1.5"
								fill="none" />
							<path
								d="M28 55 L62 55"
								stroke="#d4a017"
								stroke-width="0.8"
								stroke-dasharray="3 3" />
						</svg>
					</div>
					<div class="p-6">
						<p
							class="text-xs uppercase tracking-widest text-smoke mb-1"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									letter-spacing: 0.15em;
								">
							Tote Bag
						</p>
						<h3
							class="text-white mb-2"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-weight: 700;
									font-size: 1rem;
								">
							Canvas Tote Custom
						</h3>
						<p class="text-smoke text-sm mb-4" style="line-height: 1.6">
							Bahan canvas tebal, cocok untuk merchandise, seminar kit, dan
							gift pack eksklusif.
						</p>
						<div class="flex items-center justify-between">
							<div>
								<span class="text-xs text-smoke">Mulai dari</span>
								<div
									class="gold-text"
									style="
											font-family: &quot;Cormorant Garamond&quot;, serif;
											font-size: 1.4rem;
											font-weight: 600;
										">
									Rp 45.000<span class="text-sm text-smoke font-sans">/pcs</span>
								</div>
							</div>
							<a href="#order" class="btn-outline-gold py-2 px-4 text-xs">Pesan</a>
						</div>
					</div>
				</div>

				<!-- Bundle CTA -->
				<div
					class="product-card flex flex-col items-center justify-center text-center p-8"
					style="
							background: linear-gradient(
								135deg,
								rgba(212, 160, 23, 0.06),
								rgba(212, 160, 23, 0.02)
							);
							min-height: 340px;
						">
					<svg
						width="40"
						height="40"
						viewBox="0 0 40 40"
						fill="none"
						class="mb-4">
						<polygon
							points="20,3 37,13 37,27 20,37 3,27 3,13"
							stroke="#d4a017"
							stroke-width="1.2"
							fill="none"
							opacity="0.5" />
						<path
							d="M20 12 L20 28 M12 20 L28 20"
							stroke="#d4a017"
							stroke-width="1.5"
							stroke-linecap="round" />
					</svg>
					<h3
						class="gold-text mb-3"
						style="
								font-family: &quot;Cormorant Garamond&quot;, serif;
								font-size: 1.5rem;
							">
						Produk Lainnya?
					</h3>
					<p class="text-smoke text-sm mb-6">
						Hubungi kami untuk kebutuhan custom di luar katalog. Kami siap
						membantu!
					</p>
					<a href="#order" class="btn-gold text-xs py-3 px-6">Diskusi Sekarang</a>
				</div>
			</div>
		</div>
	</section>

	<div class="section-divider mx-6"></div>

	<!-- ===================== PROCESS ===================== -->
	<section id="process" class="py-24 px-6">
		<div class="max-w-5xl mx-auto">
			<div class="text-center mb-16">
				<p
					class="text-xs uppercase tracking-widest text-gold-500 mb-4"
					style="
							font-family: &quot;Syne&quot;, sans-serif;
							letter-spacing: 0.3em;
						">
					Cara Kerja
				</p>
				<h2
					class="gold-text mb-4"
					style="
							font-family: &quot;Cormorant Garamond&quot;, serif;
							font-size: clamp(2rem, 5vw, 3.5rem);
							font-weight: 300;
						">
					Proses Pemesanan
				</h2>
				<div class="deco-line mx-auto"></div>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
				<!-- Connecting line desktop -->
				<div
					class="hidden md:block absolute top-12 left-1/4 right-1/4 h-px"
					style="
							background: linear-gradient(
								90deg,
								rgba(212, 160, 23, 0.15),
								rgba(212, 160, 23, 0.4),
								rgba(212, 160, 23, 0.15)
							);
							width: 75%;
							left: 12%;
						"></div>

				<div class="relative text-center">
					<div class="step-num">01</div>
					<div
						class="w-10 h-10 border border-gold-500 flex items-center justify-center mx-auto mb-4 -mt-2"
						style="border-color: rgba(212, 160, 23, 0.5)">
						<svg
							width="18"
							height="18"
							fill="none"
							stroke="#d4a017"
							stroke-width="1.5"
							viewBox="0 0 24 24">
							<path
								d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
						</svg>
					</div>
					<h4
						class="text-white text-sm font-bold mb-2"
						style="font-family: &quot;Syne&quot;, sans-serif">
						Isi Form
					</h4>
					<p class="text-smoke text-sm">
						Lengkapi form pemesanan dengan detail dan upload desainmu
					</p>
				</div>

				<div class="relative text-center">
					<div class="step-num">02</div>
					<div
						class="w-10 h-10 border border-gold-500 flex items-center justify-center mx-auto mb-4 -mt-2"
						style="border-color: rgba(212, 160, 23, 0.5)">
						<svg
							width="18"
							height="18"
							fill="none"
							stroke="#d4a017"
							stroke-width="1.5"
							viewBox="0 0 24 24">
							<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
					</div>
					<h4
						class="text-white text-sm font-bold mb-2"
						style="font-family: &quot;Syne&quot;, sans-serif">
						Konfirmasi
					</h4>
					<p class="text-smoke text-sm">
						Admin menghubungi untuk konfirmasi order & total biaya
					</p>
				</div>

				<div class="relative text-center">
					<div class="step-num">03</div>
					<div
						class="w-10 h-10 border border-gold-500 flex items-center justify-center mx-auto mb-4 -mt-2"
						style="border-color: rgba(212, 160, 23, 0.5)">
						<svg
							width="18"
							height="18"
							fill="none"
							stroke="#d4a017"
							stroke-width="1.5"
							viewBox="0 0 24 24">
							<path
								d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
					</div>
					<h4
						class="text-white text-sm font-bold mb-2"
						style="font-family: &quot;Syne&quot;, sans-serif">
						Pembayaran
					</h4>
					<p class="text-smoke text-sm">
						DP 50% sebelum produksi dimulai, lunas sebelum pengiriman
					</p>
				</div>

				<div class="relative text-center">
					<div class="step-num">04</div>
					<div
						class="w-10 h-10 border border-gold-500 flex items-center justify-center mx-auto mb-4 -mt-2"
						style="border-color: rgba(212, 160, 23, 0.5)">
						<svg
							width="18"
							height="18"
							fill="none"
							stroke="#d4a017"
							stroke-width="1.5"
							viewBox="0 0 24 24">
							<path
								d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
						</svg>
					</div>
					<h4
						class="text-white text-sm font-bold mb-2"
						style="font-family: &quot;Syne&quot;, sans-serif">
						Terima Order
					</h4>
					<p class="text-smoke text-sm">
						Produk selesai dan dikirim sesuai deadline yang disepakati
					</p>
				</div>
			</div>
		</div>
	</section>

	<div class="section-divider mx-6"></div>

	<!-- ===================== TESTIMONIALS ===================== -->
	<section class="py-24 px-6">
		<div class="max-w-5xl mx-auto">
			<div class="text-center mb-14">
				<p
					class="text-xs uppercase tracking-widest text-gold-500 mb-4"
					style="
							font-family: &quot;Syne&quot;, sans-serif;
							letter-spacing: 0.3em;
						">
					Kata Mereka
				</p>
				<h2
					class="gold-text"
					style="
							font-family: &quot;Cormorant Garamond&quot;, serif;
							font-size: clamp(2rem, 5vw, 3.5rem);
							font-weight: 300;
						">
					Testimoni
				</h2>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
				<div class="testimonial-card">
					<p class="text-smoke text-sm mt-6 mb-6" style="line-height: 1.7">
						Kualitas bagus banget! Desain yang kami minta kompleks tapi
						hasilnya persis sama. Pengiriman juga tepat waktu.
					</p>
					<div class="flex items-center gap-3">
						<div
							class="w-9 h-9 rounded-full bg-graphite border border-gold flex items-center justify-center text-gold-400 text-xs font-bold"
							style="
									border-color: rgba(212, 160, 23, 0.25);
									font-family: &quot;Syne&quot;, sans-serif;
								">
							AS
						</div>
						<div>
							<div
								class="text-white text-sm font-bold"
								style="font-family: &quot;Syne&quot;, sans-serif">
								Andi S.
							</div>
							<div class="text-smoke text-xs">Owner, DistroKeren</div>
						</div>
						<div class="ml-auto text-gold-400" style="font-size: 12px">
							★★★★★
						</div>
					</div>
				</div>

				<div class="testimonial-card">
					<p class="text-smoke text-sm mt-6 mb-6" style="line-height: 1.7">
						Sudah 3x order untuk event kampus. Harga bersaing, respon admin
						cepat, hasil memuaskan. Recommended!
					</p>
					<div class="flex items-center gap-3">
						<div
							class="w-9 h-9 rounded-full bg-graphite border border-gold flex items-center justify-center text-gold-400 text-xs font-bold"
							style="
									border-color: rgba(212, 160, 23, 0.25);
									font-family: &quot;Syne&quot;, sans-serif;
								">
							RP
						</div>
						<div>
							<div
								class="text-white text-sm font-bold"
								style="font-family: &quot;Syne&quot;, sans-serif">
								Risa P.
							</div>
							<div class="text-smoke text-xs">Ketua BEM, UNY</div>
						</div>
						<div class="ml-auto text-gold-400" style="font-size: 12px">
							★★★★★
						</div>
					</div>
				</div>

				<div class="testimonial-card">
					<p class="text-smoke text-sm mt-6 mb-6" style="line-height: 1.7">
						Bahan hoodie-nya tebal dan nyaman. Cetak bordir logonya rapi.
						Pasti balik lagi untuk order berikutnya.
					</p>
					<div class="flex items-center gap-3">
						<div
							class="w-9 h-9 rounded-full bg-graphite border border-gold flex items-center justify-center text-gold-400 text-xs font-bold"
							style="
									border-color: rgba(212, 160, 23, 0.25);
									font-family: &quot;Syne&quot;, sans-serif;
								">
							DW
						</div>
						<div>
							<div
								class="text-white text-sm font-bold"
								style="font-family: &quot;Syne&quot;, sans-serif">
								Dwi W.
							</div>
							<div class="text-smoke text-xs">Manager, StartupJogja</div>
						</div>
						<div class="ml-auto text-gold-400" style="font-size: 12px">
							★★★★★
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="section-divider mx-6"></div>

	<!-- ===================== ORDER FORM ===================== -->
	<section id="order" class="py-24 px-6">
		<div class="max-w-2xl mx-auto">
			<div class="text-center mb-12">
				<p
					class="text-xs uppercase tracking-widest text-gold-500 mb-4"
					style="
							font-family: &quot;Syne&quot;, sans-serif;
							letter-spacing: 0.3em;
						">
					Mulai Order
				</p>
				<h2
					class="gold-text mb-4"
					style="
							font-family: &quot;Cormorant Garamond&quot;, serif;
							font-size: clamp(2rem, 5vw, 3.5rem);
							font-weight: 300;
						">
					Form Pemesanan
				</h2>
				<p class="text-smoke text-sm">
					Isi detail order di bawah ini. Admin akan menghubungi Anda dalam
					1×24 jam.
				</p>
			</div>

			<form action="<?= base_url('home/save_order') ?>" method="POST" class="form-panel">
				<div class="form-panel-header">
					<div class="flex items-center gap-3">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
							<polygon
								points="8,1 15,4.5 15,11.5 8,15 1,11.5 1,4.5"
								stroke="#d4a017"
								stroke-width="1"
								fill="none" />
						</svg>
						<div>
							<div
								style="
										font-family: &quot;Syne&quot;, sans-serif;
										font-weight: 700;
										font-size: 13px;
										color: #e8e8e0;
										letter-spacing: 0.05em;
									">
								Detail Pemesanan
							</div>
							<div style="font-size: 11px; color: #666; margin-top: 2px">
								Order akan masuk ke antrian dan dijadwalkan oleh admin.
							</div>
						</div>
					</div>
				</div>

				<div style="padding: 28px">
					<!-- Customer Info -->
					<div class="mb-6">
						<div
							class="text-xs uppercase tracking-widest text-gold-600 mb-4"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									letter-spacing: 0.2em;
									border-bottom: 1px solid rgba(212, 160, 23, 0.1);
									padding-bottom: 8px;
								">
							Informasi Pemesan
						</div>

						<div class="form-group">
							<label class="form-label">Nama Lengkap</label>
							<input
								name="customer_name"
								type="text"
								class="form-input"
								placeholder="Masukkan nama lengkap" />
						</div>

						<div class="form-group">
							<label class="form-label">No. HP / WhatsApp</label>
							<input
								name="phone"
								type="text"
								class="form-input"
								placeholder="08xx-xxxx-xxxx" />
						</div>
					</div>

					<!-- Product Info -->
					<div class="mb-6">
						<div
							class="text-xs uppercase tracking-widest text-gold-600 mb-4"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									letter-spacing: 0.2em;
									border-bottom: 1px solid rgba(212, 160, 23, 0.1);
									padding-bottom: 8px;
								">
							Detail Produk
						</div>

						<div class="form-group">
							<label class="form-label">Jenis Produk</label>
							<select
								name="product_type"
								id="product-type"
								class="form-input"
								onchange="calcDuration()">
								<option value="10">T-Shirt (DTF Print)</option>
								<option value="12">T-Shirt (Screen Print)</option>
								<option value="15">Hoodie</option>
								<option value="13">Polo Shirt</option>
								<option value="8">Tote Bag</option>
							</select>
						</div>

						<div class="form-group">
							<label class="form-label">Deadline Pengiriman</label>
							<input
								name="deadline"
								type="date"
								id="deadline"
								class="form-input"
								onchange="calcDuration()" />
						</div>
					</div>

					<!-- Order Items -->
					<div class="mb-6">
						<div
							class="text-xs uppercase tracking-widest text-gold-600 mb-4"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									letter-spacing: 0.2em;
									border-bottom: 1px solid rgba(212, 160, 23, 0.1);
									padding-bottom: 8px;
								">
							Detail Item
						</div>

						<label class="form-label">Item Pesanan</label>
						<table
							id="items-table"
							style="width: 100%; border-collapse: collapse">
							<thead>
								<tr>
									<th
										style="
												text-align: left;
												padding-bottom: 10px;
												font-family: &quot;Syne&quot;, sans-serif;
												font-size: 10px;
												font-weight: 700;
												letter-spacing: 0.12em;
												text-transform: uppercase;
												color: rgba(212, 160, 23, 0.6);
											">
										Ukuran
									</th>
									<th
										style="
												text-align: left;
												padding-bottom: 10px;
												font-family: &quot;Syne&quot;, sans-serif;
												font-size: 10px;
												font-weight: 700;
												letter-spacing: 0.12em;
												text-transform: uppercase;
												color: rgba(212, 160, 23, 0.6);
											">
										Qty
									</th>
									<th style="width: 36px"></th>
								</tr>
							</thead>
							<tbody id="items-body">
								<tr>
									<td style="padding: 4px 12px 4px 0; width: 120px">
										<select name="size[]" class="form-input">
											<option>S</option>
											<option>M</option>
											<option>L</option>
											<option>XL</option>
											<option>XXL</option>
										</select>
									</td>
									<td style="padding: 4px 12px 4px 0">
										<input
											name="qty[]"
											type="number"
											class="form-input qty-input"
											placeholder="0"
											min="1"
											oninput="calcDuration()" />
									</td>
									<td style="padding: 4px 0; vertical-align: middle">
										<button
											type="button"
											onclick="removeRow(this)"
											class="remove-btn">
											✕
										</button>
									</td>
								</tr>
							</tbody>
						</table>

						<button
							type="button"
							onclick="addRow()"
							style="
									margin-top: 12px;
									background: rgba(212, 160, 23, 0.07);
									border: 1px dashed rgba(212, 160, 23, 0.3);
									color: #d4a017;
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 11px;
									font-weight: 600;
									letter-spacing: 0.1em;
									text-transform: uppercase;
									padding: 8px 16px;
									cursor: pointer;
									width: 100%;
									transition: all 0.2s;
								"
							onmouseover="this.style.background = 'rgba(212,160,23,0.12)'"
							onmouseout="this.style.background = 'rgba(212,160,23,0.07)'">
							+ Tambah Ukuran
						</button>
					</div>

					<!-- Estimation -->
					<div id="duration-box" style="display: none" class="est-box">
						<div
							style="
									font-size: 10px;
									text-transform: uppercase;
									letter-spacing: 0.12em;
									color: #666;
									font-family: &quot;Syne&quot;, sans-serif;
									margin-bottom: 4px;
								">
							Estimasi Waktu Produksi
						</div>
						<div
							id="duration-val"
							class="gold-text"
							style="
									font-family: &quot;Cormorant Garamond&quot;, serif;
									font-size: 2rem;
									font-weight: 600;
								"></div>
						<div
							id="duration-note"
							style="font-size: 11px; color: #666; margin-top: 4px"></div>
					</div>

					<input type="hidden" name="est_duration" id="est-duration-hidden">

					<!-- Additional -->
					<div class="mb-2">
						<div
							class="text-xs uppercase tracking-widest text-gold-600 mb-4"
							style="
									font-family: &quot;Syne&quot;, sans-serif;
									font-size: 9px;
									letter-spacing: 0.2em;
									border-bottom: 1px solid rgba(212, 160, 23, 0.1);
									padding-bottom: 8px;
								">
							Tambahan
						</div>

						<div class="form-group">
							<label class="form-label">Catatan Khusus</label>
							<textarea
								name="notes"
								class="form-input"
								rows="3"
								placeholder="Warna bahan, instruksi desain, atau catatan lainnya..."></textarea>
						</div>

						<div class="form-group">
							<label class="form-label">Upload File Desain</label>
							<input
								name="design_file"
								type="file"
								class="form-input"
								accept=".jpg,.jpeg,.png,.pdf,.ai,.psd" />
							<div style="font-size: 10px; color: #555; margin-top: 5px">
								Format: JPG, PNG, PDF, AI, PSD. Maks 20MB.
							</div>
						</div>
					</div>

					<!-- Submit -->
					<div
						style="
								display: flex;
								gap: 12px;
								justify-content: flex-end;
								padding-top: 8px;
								border-top: 1px solid rgba(212, 160, 23, 0.1);
								margin-top: 20px;
							">
						<button
							type="reset"
							class="btn-outline-gold"
							onclick="clearForm()">
							Reset
						</button>
						<button type="submit" class="btn-gold" onclick="submitForm()">
							Kirim Pesanan
							<svg
								width="14"
								height="14"
								fill="none"
								stroke="currentColor"
								stroke-width="2"
								viewBox="0 0 24 24">
								<path d="M5 12h14M12 5l7 7-7 7" />
							</svg>
						</button>
					</div>
				</div>
			</form>

			<!-- Contact info -->
			<div
				class="mt-8 flex flex-col sm:flex-row gap-4 justify-center text-center">
				<div class="flex items-center gap-2 justify-center">
					<svg
						width="14"
						height="14"
						fill="none"
						stroke="rgba(212,160,23,0.6)"
						stroke-width="1.5"
						viewBox="0 0 24 24">
						<path
							d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
					</svg>
					<span class="text-smoke text-xs">+62 812-3456-7890</span>
				</div>
				<div class="hidden sm:block text-gold-600 opacity-30">|</div>
				<div class="flex items-center gap-2 justify-center">
					<svg
						width="14"
						height="14"
						fill="none"
						stroke="rgba(212,160,23,0.6)"
						stroke-width="1.5"
						viewBox="0 0 24 24">
						<path
							d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
					</svg>
					<span class="text-smoke text-xs">hello@aurum-apparel.id</span>
				</div>
				<div class="hidden sm:block text-gold-600 opacity-30">|</div>
				<div class="flex items-center gap-2 justify-center">
					<svg
						width="14"
						height="14"
						fill="none"
						stroke="rgba(212,160,23,0.6)"
						stroke-width="1.5"
						viewBox="0 0 24 24">
						<path
							d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
						<path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
					</svg>
					<span class="text-smoke text-xs">Yogyakarta, Indonesia</span>
				</div>
			</div>
		</div>
	</section>

	<!-- ===================== FOOTER ===================== -->
	<footer
		style="
				background: #080808;
				border-top: 1px solid rgba(212, 160, 23, 0.1);
				padding: 40px 24px 24px;
			">
		<div class="max-w-6xl mx-auto">
			<div
				class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
				<div class="flex items-center gap-3">
					<svg width="24" height="24" viewBox="0 0 28 28" fill="none">
						<polygon
							points="14,2 26,9 26,19 14,26 2,19 2,9"
							stroke="#d4a017"
							stroke-width="1.5"
							fill="none" />
					</svg>
					<span
						style="
								font-family: &quot;Syne&quot;, sans-serif;
								font-weight: 800;
								letter-spacing: 0.25em;
								color: #d4a017;
								font-size: 14px;
							">AURUM</span>
				</div>
				<p
					class="text-smoke text-xs text-center"
					style="font-family: &quot;DM Sans&quot;, sans-serif">
					Premium Custom Apparel · Yogyakarta
				</p>
				<div class="flex gap-4">
					<a
						href="#"
						class="text-smoke hover:text-gold-400 transition-colors">
						<svg
							width="16"
							height="16"
							fill="currentColor"
							viewBox="0 0 24 24">
							<path
								d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.624 0 11.99-5.367 11.99-11.987C24.007 5.367 18.641.029 12.017.029z" />
						</svg>
					</a>
					<a
						href="#"
						class="text-smoke hover:text-gold-400 transition-colors">
						<svg
							width="16"
							height="16"
							fill="currentColor"
							viewBox="0 0 24 24">
							<path
								d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
						</svg>
					</a>
					<a
						href="#"
						class="text-smoke hover:text-gold-400 transition-colors">
						<svg
							width="16"
							height="16"
							fill="currentColor"
							viewBox="0 0 24 24">
							<path
								d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
						</svg>
					</a>
				</div>
			</div>
			<div class="section-divider"></div>
			<div class="pt-6 text-center">
				<p
					class="text-xs text-ash"
					style="font-family: &quot;DM Sans&quot;, sans-serif">
					© 2025 AURUM Custom Apparel. All rights reserved.
				</p>
			</div>
		</div>
	</footer>

	<!-- ===================== SUCCESS MODAL ===================== -->
	<div
		id="success-modal"
		style="
				display: none;
				position: fixed;
				inset: 0;
				z-index: 100;
				background: rgba(0, 0, 0, 0.85);
				backdrop-filter: blur(8px);
			"
		class="flex items-center justify-center px-6">
		<div
			style="
					background: #111;
					border: 1px solid rgba(212, 160, 23, 0.3);
					max-width: 400px;
					width: 100%;
					padding: 40px;
					text-align: center;
				">
			<div class="mb-5">
				<svg
					width="48"
					height="48"
					viewBox="0 0 48 48"
					fill="none"
					class="mx-auto">
					<polygon
						points="24,3 44,14 44,34 24,45 4,34 4,14"
						stroke="#d4a017"
						stroke-width="1.5"
						fill="rgba(212,160,23,0.06)" />
					<path
						d="M16 24 L21 29 L32 18"
						stroke="#d4a017"
						stroke-width="2"
						stroke-linecap="round"
						stroke-linejoin="round" />
				</svg>
			</div>
			<h3
				class="gold-text mb-3"
				style="
						font-family: &quot;Cormorant Garamond&quot;, serif;
						font-size: 1.8rem;
						font-weight: 400;
					">
				Pesanan Terkirim!
			</h3>
			<p class="text-smoke text-sm mb-6" style="line-height: 1.7">
				Terima kasih! Order Anda sudah kami terima. Admin akan menghubungi
				Anda via WhatsApp dalam 1×24 jam.
			</p>
			<button onclick="closeModal()" class="btn-gold w-full justify-center">
				Tutup
			</button>
		</div>
	</div>


</body>

</html>
