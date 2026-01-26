<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dive Paradise - Liveaboard Adventures</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      @keyframes slideIn {
        from {
          opacity: 0;
          transform: translateY(30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      .animate-slide-in {
        animation: slideIn 0.8s ease-out forwards;
      }
      .slideshow-item {
        transition: opacity 1s ease-in-out;
      }
    </style>
  </head>
  <body class="bg-sky-50 text-gray-800">
    <!-- Navigation -->
    <header
      id="navbar"
      class="fixed top-0 left-0 w-full z-50 backdrop-blur-lg bg-white/10 border-b border-white/20 shadow-sm transition-all duration-300"
    >
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-20">
          <!-- Logo -->
          <div class="text-white text-2xl font-bold tracking-wide">
            LiveAboardTrips
          </div>

          <!-- Desktop Menu -->
          <nav
            class="hidden md:flex items-center space-x-8 text-white font-medium"
          >
            <a href="#" class="hover:text-cyan-200 transition">Home</a>
            <a href="#" class="hover:text-cyan-200 transition">Destinations</a>
            <a href="#" class="hover:text-cyan-200 transition">Trips</a>
            <a href="#" class="hover:text-cyan-200 transition">Contact Us</a>

            <a
              href="#"
              class="bg-white text-blue-600 px-5 py-2 rounded-full font-semibold hover:bg-blue-50 transition shadow-lg"
            >
              Book Now
            </a>

            <a
              href="#"
              class="border border-white/70 px-4 py-2 rounded-full hover:bg-white/10 transition"
            >
              Partner Login
            </a>
          </nav>

          <!-- Mobile Toggle Button -->
          <button id="menuBtn" class="md:hidden text-white focus:outline-none">
            <svg
              class="w-7 h-7"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div
        id="mobileMenu"
        class="md:hidden overflow-hidden max-h-0 opacity-0 transition-all duration-500 ease-in-out px-6 bg-white/10 backdrop-blur-xl border-t border-white/20"
      >
        <div class="flex flex-col space-y-4 text-white font-medium py-6">
          <a href="#" class="hover:text-cyan-200 transition">Home</a>
          <a href="#" class="hover:text-cyan-200 transition">Destinations</a>
          <a href="#" class="hover:text-cyan-200 transition">Trips</a>
          <a href="#" class="hover:text-cyan-200 transition">Contact Us</a>

          <a
            href="#"
            class="bg-white text-blue-600 px-5 py-3 rounded-full font-semibold text-center shadow-lg"
          >
            Book Now
          </a>

          <a
            href="#"
            class="border border-white/70 px-4 py-3 rounded-full text-center hover:bg-white/10 transition"
          >
            Partner Login
          </a>
        </div>
      </div>
    </header>

    <!-- Hero Search Section -->
    <section
      class="relative min-h-screen pt-28 flex items-center overflow-hidden bg-gradient-to-br from-blue-400 via-cyan-300 to-teal-400"
    >
      <!-- Overlay -->
      <div class="absolute inset-0 bg-blue-900 opacity-20"></div>

      <div
        class="relative z-10 w-full max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center"
      >
        <!-- LEFT SIDE — HEADLINE -->
        <div class="text-white animate-slide-in">
          <h1
            class="text-5xl md:text-7xl font-bold leading-tight drop-shadow-lg mb-6"
          >
            Your adventure<br />starts here.
          </h1>

          <p class="text-lg md:text-xl opacity-95 mb-8 max-w-xl">
            Discover world-class dive destinations, luxury liveaboards, and
            unforgettable underwater experiences.
          </p>

          <div class="hidden md:block">
            <button
              class="bg-white text-blue-600 hover:bg-blue-50 px-10 py-4 rounded-full text-lg font-semibold transition-all duration-300 hover:scale-105 shadow-2xl"
            >
              Explore Trips
            </button>
          </div>
        </div>

        <!-- RIGHT SIDE — SEARCH CARD -->
        <div
          class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl p-8 w-full max-w-xl mx-auto"
        >
          <h2 class="text-2xl font-bold text-gray-800 mb-6">
            Find Your Dive Destination
          </h2>

          <div class="space-y-5">
            <!-- Location -->
            <div>
              <label class="block text-sm font-semibold text-gray-600 mb-2">
                Destination
              </label>
              <input
                type="text"
                placeholder="e.g. Tubbataha, Maldives, Raja Ampat"
                class="w-full px-5 py-4 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-400 outline-none"
              />
            </div>

            <!-- Date -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                  Check-in
                </label>
                <input
                  type="date"
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-400 outline-none"
                />
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                  Duration
                </label>
                <select
                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-400 outline-none"
                >
                  <option>3–4 Nights</option>
                  <option>5–7 Nights</option>
                  <option>8–10 Nights</option>
                  <option>10+ Nights</option>
                </select>
              </div>
            </div>

            <!-- Divers -->
            <div>
              <label class="block text-sm font-semibold text-gray-600 mb-2">
                Divers
              </label>
              <select
                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-cyan-400 outline-none"
              >
                <option>1 Diver</option>
                <option>2 Divers</option>
                <option>3 Divers</option>
                <option>4+ Divers</option>
              </select>
            </div>

            <!-- Search Button -->
            <button
              class="w-full bg-gradient-to-r from-cyan-500 to-teal-500 text-white font-semibold py-4 rounded-xl text-lg shadow-lg hover:scale-[1.02] transition-all duration-300"
            >
              Search Liveaboards
            </button>
          </div>
        </div>
      </div>

      <!-- Scroll Indicator -->
      <div
        class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce"
      >
        <svg
          class="w-8 h-8 text-white"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M19 9l-7 7-7-7"
          />
        </svg>
      </div>
    </section>

    <!-- Services Section -->
    <section class="py-20 px-6 bg-white">
      <div class="max-w-7xl mx-auto">
        <h2
          class="text-5xl font-bold text-center mb-16 bg-gradient-to-r from-blue-600 to-teal-500 bg-clip-text text-transparent"
        >
          Why Choose Us
        </h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- Feature 1 -->
          <div
            class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 hover:scale-105 border border-blue-100"
          >
            <div
              class="bg-gradient-to-br from-blue-500 to-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6"
            >
              <svg
                class="w-8 h-8 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                ></path>
              </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-blue-700">
              Certified Crew
            </h3>
            <p class="text-gray-600 leading-relaxed">
              Professional dive masters and instructors with years of experience
              ensuring your safety and unforgettable underwater adventures.
            </p>
          </div>

          <!-- Feature 2 -->
          <div
            class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 hover:scale-105 border border-teal-100"
          >
            <div
              class="bg-gradient-to-br from-teal-500 to-teal-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6"
            >
              <svg
                class="w-8 h-8 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                ></path>
              </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-teal-700">Luxury Cabins</h3>
            <p class="text-gray-600 leading-relaxed">
              Comfortable air-conditioned cabins with ensuite bathrooms,
              providing a relaxing retreat after exciting dives.
            </p>
          </div>

          <!-- Feature 3 -->
          <div
            class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 hover:scale-105 border border-cyan-100"
          >
            <div
              class="bg-gradient-to-br from-cyan-500 to-blue-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6"
            >
              <svg
                class="w-8 h-8 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"
                ></path>
              </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-cyan-700">
              Gourmet Dining
            </h3>
            <p class="text-gray-600 leading-relaxed">
              Freshly prepared meals with international and local cuisine,
              accommodating dietary preferences and restrictions.
            </p>
          </div>

          <!-- Feature 4 -->
          <div
            class="bg-gradient-to-br from-blue-50 to-teal-50 rounded-3xl p-8 hover:shadow-xl transition-all duration-300 hover:scale-105 border border-blue-100"
          >
            <div
              class="bg-gradient-to-br from-blue-600 to-teal-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-6"
            >
              <svg
                class="w-8 h-8 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                ></path>
              </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4 text-blue-700">
              Best Dive Sites
            </h3>
            <p class="text-gray-600 leading-relaxed">
              Carefully selected routes covering pristine reefs, thrilling drift
              dives, and encounters with magnificent marine life.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Underwater Gallery Section -->
    <section
      class="py-20 px-6 bg-gradient-to-br from-cyan-100 via-blue-50 to-teal-100"
    >
      <div class="max-w-7xl mx-auto">
        <h2 class="text-5xl font-bold text-center mb-16 text-blue-800">
          Underwater Wonders
        </h2>
        <div class="grid md:grid-cols-3 gap-6">
          <div
            class="group relative overflow-hidden rounded-3xl h-80 bg-blue-200 shadow-lg"
          >
            <img
              src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800"
              alt="Marine Life"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-blue-900 via-transparent to-transparent opacity-0 group-hover:opacity-90 transition-opacity duration-300 flex items-end p-6"
            >
              <p class="text-white text-xl font-semibold">
                Vibrant Coral Gardens
              </p>
            </div>
          </div>
          <div
            class="group relative overflow-hidden rounded-3xl h-80 bg-blue-200 shadow-lg"
          >
            <img
              src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800"
              alt="Diving"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-teal-900 via-transparent to-transparent opacity-0 group-hover:opacity-90 transition-opacity duration-300 flex items-end p-6"
            >
              <p class="text-white text-xl font-semibold">
                Majestic Sea Creatures
              </p>
            </div>
          </div>
          <div
            class="group relative overflow-hidden rounded-3xl h-80 bg-blue-200 shadow-lg"
          >
            <img
              src="https://images.unsplash.com/photo-1682687220742-aba13b6e50ba?w=800"
              alt="Reef"
              class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-cyan-900 via-transparent to-transparent opacity-0 group-hover:opacity-90 transition-opacity duration-300 flex items-end p-6"
            >
              <p class="text-white text-xl font-semibold">
                Crystal Clear Waters
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section
      class="py-20 px-6 bg-gradient-to-br from-cyan-100 via-blue-50 to-teal-100"
    >
      <div class="max-w-7xl mx-auto">
        <h2
          class="text-4xl md:text-5xl font-bold text-center mb-14 text-blue-800"
        >
          Underwater Wonders
        </h2>

        <!-- Masonry Columns -->
        <div class="columns-2 sm:columns-3 lg:columns-4 gap-4 space-y-4">
          <!-- TILE -->
          <div
            class="break-inside-avoid relative group overflow-hidden rounded-2xl shadow-lg cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800"
              class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110"
              alt="Coral Gardens"
              loading="lazy"
            />
            <div
              class="absolute inset-0 flex items-end p-4 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white font-semibold text-lg"
            >
              Coral Gardens
            </div>
          </div>

          <div
            class="break-inside-avoid relative group overflow-hidden rounded-2xl shadow-lg cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=600"
              class="w-full h-40 object-cover transition-transform duration-500 group-hover:scale-110"
              alt="Sea Turtles"
              loading="lazy"
            />
            <div
              class="absolute inset-0 flex items-end p-4 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white font-semibold"
            >
              Sea Turtles
            </div>
          </div>

          <div
            class="break-inside-avoid relative group overflow-hidden rounded-2xl shadow-lg cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1682687220742-aba13b6e50ba?w=1000"
              class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
              alt="Schools of Fish"
              loading="lazy"
            />
            <div
              class="absolute inset-0 flex items-end p-4 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white font-semibold"
            >
              Schools of Fish
            </div>
          </div>

          <div
            class="break-inside-avoid relative group overflow-hidden rounded-2xl shadow-lg cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1583212292454-1fe6229603b7?w=600"
              class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-110"
              alt="Manta Rays"
              loading="lazy"
            />
            <div
              class="absolute inset-0 flex items-end p-4 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white font-semibold text-lg"
            >
              Manta Rays
            </div>
          </div>

          <div
            class="break-inside-avoid relative group overflow-hidden rounded-2xl shadow-lg cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1546026423-cc4642628d2b?w=600"
              class="w-full h-44 object-cover transition-transform duration-500 group-hover:scale-110"
              alt="Reef Life"
              loading="lazy"
            />
            <div
              class="absolute inset-0 flex items-end p-4 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white font-semibold"
            >
              Reef Life
            </div>
          </div>

          <div
            class="break-inside-avoid relative group overflow-hidden rounded-2xl shadow-lg cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1583212292454-1fe6229603b7?w=1000"
              class="w-full h-56 object-cover transition-transform duration-500 group-hover:scale-110"
              alt="Shark Encounters"
              loading="lazy"
            />
            <div
              class="absolute inset-0 flex items-end p-4 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white font-semibold"
            >
              Shark Encounters
            </div>
          </div>

          <div
            class="break-inside-avoid relative group overflow-hidden rounded-2xl shadow-lg cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=600"
              class="w-full h-60 object-cover transition-transform duration-500 group-hover:scale-110"
              alt="Wreck Diving"
              loading="lazy"
            />
            <div
              class="absolute inset-0 flex items-end p-4 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white font-semibold"
            >
              Wreck Diving
            </div>
          </div>

          <div
            class="break-inside-avoid relative group overflow-hidden rounded-2xl shadow-lg cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=600"
              class="w-full h-40 object-cover transition-transform duration-500 group-hover:scale-110"
              alt="Macro Life"
              loading="lazy"
            />
            <div
              class="absolute inset-0 flex items-end p-4 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white font-semibold"
            >
              Macro Life
            </div>
          </div>

          <div
            class="break-inside-avoid relative group overflow-hidden rounded-2xl shadow-lg cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1682687220742-aba13b6e50ba?w=600"
              class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
              alt="Octopus"
              loading="lazy"
            />
            <div
              class="absolute inset-0 flex items-end p-4 bg-gradient-to-t from-black/80 via-black/30 to-transparent text-white font-semibold"
            >
              Octopus
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Destinations Slideshow -->
    <section class="py-20 px-6 bg-white">
      <div class="max-w-7xl mx-auto">
        <h2
          class="text-5xl font-bold text-center mb-16 bg-gradient-to-r from-blue-600 to-teal-500 bg-clip-text text-transparent"
        >
          Featured Destinations
        </h2>
        <div
          class="relative rounded-3xl overflow-hidden shadow-2xl"
          style="height: 500px"
        >
          <div id="slideshow" class="relative w-full h-full">
            <div class="slideshow-item absolute inset-0">
              <img
                src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1200"
                alt="Destination 1"
                class="w-full h-full object-cover"
              />
              <div
                class="absolute inset-0 bg-gradient-to-t from-blue-900 via-transparent to-transparent flex items-end p-12"
              >
                <div>
                  <h3 class="text-4xl font-bold text-white mb-2">Raja Ampat</h3>
                  <p class="text-gray-200 text-lg">
                    The heart of coral diversity
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div
            class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex gap-3"
          >
            <button
              onclick="changeSlide(0)"
              class="w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 transition-opacity"
            ></button>
            <button
              onclick="changeSlide(1)"
              class="w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 transition-opacity"
            ></button>
            <button
              onclick="changeSlide(2)"
              class="w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 transition-opacity"
            ></button>
          </div>
        </div>
      </div>
    </section>

    <!-- Partner Vessels Section -->
    <section class="py-20 px-6 bg-sky-50">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-5xl font-bold text-center mb-16 text-blue-800">
          Our Fleet of Vessels
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center">
          <div
            class="bg-white rounded-2xl p-8 flex items-center justify-center h-32 hover:shadow-lg transition-all duration-300 border border-blue-100"
          >
            <span class="text-2xl font-bold text-blue-600"
              >MV Ocean Explorer</span
            >
          </div>
          <div
            class="bg-white rounded-2xl p-8 flex items-center justify-center h-32 hover:shadow-lg transition-all duration-300 border border-teal-100"
          >
            <span class="text-2xl font-bold text-teal-600"
              >SS Blue Horizon</span
            >
          </div>
          <div
            class="bg-white rounded-2xl p-8 flex items-center justify-center h-32 hover:shadow-lg transition-all duration-300 border border-cyan-100"
          >
            <span class="text-2xl font-bold text-cyan-600">MV Coral Dream</span>
          </div>
          <div
            class="bg-white rounded-2xl p-8 flex items-center justify-center h-32 hover:shadow-lg transition-all duration-300 border border-blue-100"
          >
            <span class="text-2xl font-bold text-blue-600">SS Wave Rider</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section
      class="py-20 px-6 bg-gradient-to-br from-blue-100 via-cyan-50 to-teal-100"
    >
      <div class="max-w-7xl mx-auto">
        <h2 class="text-5xl font-bold text-center mb-16 text-blue-800">
          Diver Reviews
        </h2>
        <div class="grid md:grid-cols-3 gap-8">
          <div
            class="bg-white rounded-3xl p-8 border border-blue-200 hover:shadow-xl transition-all duration-300"
          >
            <div class="flex items-center mb-6">
              <div
                class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-2xl font-bold text-white"
              >
                MR
              </div>
              <div class="ml-4">
                <h4 class="font-bold text-blue-800 text-lg">Marcus Rivera</h4>
                <p class="text-gray-500 text-sm">PADI Divemaster</p>
              </div>
            </div>
            <p class="text-gray-700 leading-relaxed">
              "Best liveaboard experience ever! The crew was knowledgeable, the
              boat was pristine, and the dive sites were absolutely stunning.
              Can't wait to book again!"
            </p>
          </div>
          <div
            class="bg-white rounded-3xl p-8 border border-teal-200 hover:shadow-xl transition-all duration-300"
          >
            <div class="flex items-center mb-6">
              <div
                class="w-16 h-16 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center text-2xl font-bold text-white"
              >
                LK
              </div>
              <div class="ml-4">
                <h4 class="font-bold text-teal-800 text-lg">Lisa Kim</h4>
                <p class="text-gray-500 text-sm">Advanced Open Water</p>
              </div>
            </div>
            <p class="text-gray-700 leading-relaxed">
              "The accommodations were luxurious and the food was incredible.
              Saw manta rays, sharks, and countless tropical fish. This trip
              exceeded all expectations!"
            </p>
          </div>
          <div
            class="bg-white rounded-3xl p-8 border border-cyan-200 hover:shadow-xl transition-all duration-300"
          >
            <div class="flex items-center mb-6">
              <div
                class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-2xl font-bold text-white"
              >
                TB
              </div>
              <div class="ml-4">
                <h4 class="font-bold text-cyan-800 text-lg">Tom Bradley</h4>
                <p class="text-gray-500 text-sm">Rescue Diver</p>
              </div>
            </div>
            <p class="text-gray-700 leading-relaxed">
              "Professional crew, amazing dive sites, and great value for money.
              The itinerary was perfect with a good balance of diving and
              relaxation. Highly recommended!"
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Us Section -->
    <section class="py-20 px-6 bg-white">
      <div class="max-w-4xl mx-auto">
        <h2
          class="text-5xl font-bold text-center mb-6 bg-gradient-to-r from-blue-600 to-teal-500 bg-clip-text text-transparent"
        >
          Book Your Dive Trip
        </h2>
        <p class="text-center text-gray-600 text-xl mb-12">
          Ready to explore the underwater world? Get in touch with us today!
        </p>
        <div
          class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-3xl p-10 border border-blue-200 shadow-xl"
        >
          <div class="space-y-6">
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2"
                >Email Address</label
              >
              <input
                type="email"
                placeholder="your.email@example.com"
                class="w-full px-6 py-4 rounded-2xl bg-white border border-blue-200 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 transition-all duration-300"
              />
            </div>
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2"
                >Contact Number</label
              >
              <input
                type="tel"
                placeholder="+1 (555) 123-4567"
                class="w-full px-6 py-4 rounded-2xl bg-white border border-blue-200 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-300 transition-all duration-300"
              />
            </div>
            <div>
              <label class="block text-gray-700 text-sm font-semibold mb-2"
                >Your Message</label
              >
              <textarea
                rows="6"
                placeholder="Tell us about your diving experience and preferences..."
                class="w-full px-6 py-4 rounded-2xl bg-white border border-blue-200 text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 transition-all duration-300 resize-none"
              ></textarea>
            </div>
            <button
              class="w-full bg-gradient-to-r from-blue-600 to-teal-500 hover:from-blue-500 hover:to-teal-400 py-4 rounded-2xl text-white font-bold text-lg transition-all duration-300 hover:scale-105 shadow-lg"
            >
              Send Inquiry
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-900 py-12 px-6">
      <div class="max-w-7xl mx-auto text-center">
        <p class="text-blue-100 text-lg mb-4">
          © 2026 Dive Paradise Liveaboards. All rights reserved.
        </p>
        <div class="flex justify-center gap-6">
          <a
            href="#"
            class="text-blue-200 hover:text-white transition-colors duration-300"
            >Privacy Policy</a
          >
          <a
            href="#"
            class="text-blue-200 hover:text-white transition-colors duration-300"
            >Terms of Service</a
          >
          <a
            href="#"
            class="text-blue-200 hover:text-white transition-colors duration-300"
            >Dive Safety</a
          >
        </div>
      </div>
    </footer>

    <script>
      const slides = [
        {
          img: "https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1200",
          title: "Raja Ampat",
          desc: "The heart of coral diversity",
        },
        {
          img: "https://images.unsplash.com/photo-1583212292454-1fe6229603b7?w=1200",
          title: "Komodo National Park",
          desc: "Incredible marine biodiversity",
        },
        {
          img: "https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=1200",
          title: "Maldives Atolls",
          desc: "Pristine reefs and manta encounters",
        },
      ];

      let currentSlide = 0;

      function changeSlide(index) {
        currentSlide = index;
        updateSlide();
      }

      function updateSlide() {
        const slideshow = document.getElementById("slideshow");
        const slide = slides[currentSlide];

        slideshow.innerHTML = `
                <div class="slideshow-item absolute inset-0">
                    <img src="${slide.img}" alt="Destination ${currentSlide + 1}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-900 via-transparent to-transparent flex items-end p-12">
                        <div>
                            <h3 class="text-4xl font-bold text-white mb-2">${slide.title}</h3>
                            <p class="text-gray-200 text-lg">${slide.desc}</p>
                        </div>
                    </div>
                </div>
            `;

        // Update dots
        const buttons = document.querySelectorAll(
          'button[onclick^="changeSlide"]',
        );
        buttons.forEach((btn, idx) => {
          if (idx === currentSlide) {
            btn.classList.remove("opacity-50");
            btn.classList.add("opacity-100");
          } else {
            btn.classList.remove("opacity-100");
            btn.classList.add("opacity-50");
          }
        });
      }

      // Auto-advance slideshow
      setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length;
        updateSlide();
      }, 5000);

      //mobile menu controller
      const menuBtn = document.getElementById("menuBtn");
      const mobileMenu = document.getElementById("mobileMenu");

      let isOpen = false;

      menuBtn.addEventListener("click", () => {
        if (!isOpen) {
          mobileMenu.classList.remove("max-h-0", "opacity-0");
          mobileMenu.classList.add("max-h-[500px]", "opacity-100");
        } else {
          mobileMenu.classList.add("max-h-0", "opacity-0");
          mobileMenu.classList.remove("max-h-[500px]", "opacity-100");
        }
        isOpen = !isOpen;
      });

      //nav bar on scrol controllere e
      const navbar = document.getElementById("navbar");

      window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
          navbar.classList.remove("bg-white/10", "border-white/20");
          navbar.classList.add("bg-white/90", "border-gray-200", "shadow-md");

          // Change text color for readability
          navbar.querySelectorAll("a, div, button, svg").forEach((el) => {
            el.classList.remove("text-white");
            el.classList.add("text-gray-800");
          });
        } else {
          navbar.classList.add("bg-white/10", "border-white/20");
          navbar.classList.remove(
            "bg-white/90",
            "border-gray-200",
            "shadow-md",
          );

          navbar.querySelectorAll("a, div, button, svg").forEach((el) => {
            if (el.textContent.toLocaleLowerCase().trim() === "book now") {
              el.classList.add("text-blue");
            } else {
              el.classList.add("text-white");
            }
            el.classList.remove("text-gray-800");
          });
        }
      });
    </script>
  </body>
</html>
