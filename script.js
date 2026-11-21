// =========================================================================
// FLIPPLAY - THE VIBE GRID (MODUL 2 DEMO)
// =========================================================================

// 1. State Management
let mediaData = [];
let currentAura = 'ALL';
let searchKeyword = '';

// 2. DOM Elements Selection
const vibeGrid = document.getElementById('vibe-grid');
const loadingState = document.getElementById('loading-state');
const errorState = document.getElementById('error-state');
const errorMessage = document.getElementById('error-message');
const searchInput = document.getElementById('search-input');
const auraButtons = document.querySelectorAll('.aura-btn');
const retryBtn = document.getElementById('retry-btn');
const contactForm = document.getElementById('contact-form');

// API Endpoint Utama
const KITSU_API_URL = 'https://kitsu.io/api/edge/anime?page%5Blimit%5D=20&sort=-averageRating&include=categories';

// =========================================================================
// 3. Fetch API (Mengambil Data Asynchronous)
// =========================================================================
const fetchVibeData = async () => {
  try {
    loadingState.classList.remove('hidden');
    errorState.classList.add('hidden');
    vibeGrid.innerHTML = '';

    // Ambil data dari API Publik Kitsu
    let response;
    try {
      response = await fetch(KITSU_API_URL);
    } catch (error) {
      throw new Error('Tidak dapat terhubung ke API. Periksa koneksi internet Anda, lalu coba lagi.');
    }

    if (!response.ok) {
      throw new Error(`API sedang tidak dapat diakses (HTTP ${response.status}). Silakan coba lagi.`);
    }

    const jsonResult = await response.json();
    if (!Array.isArray(jsonResult.data)) {
      throw new Error('Format data dari API tidak sesuai. Silakan coba lagi nanti.');
    }

    const includedCategories = new Map(
      (jsonResult.included || [])
        .filter(item => item.type === 'categories')
        .map(item => [item.id, item.attributes.title])
    );

    mediaData = jsonResult.data.map((item) => {
      const attributes = item.attributes || {};
      const categoryRelationships = item.relationships?.categories?.data || [];

      return {
        url: `https://kitsu.io/anime/${encodeURIComponent(attributes.slug || item.id)}`,
        title: attributes.titles?.en || attributes.canonicalTitle || attributes.titles?.en_jp,
        score: attributes.averageRating ? Number(attributes.averageRating) / 10 : null,
        episodes: attributes.episodeCount,
        type: attributes.subtype ? attributes.subtype.toUpperCase() : 'Media',
        genres: categoryRelationships
          .map(category => ({ name: includedCategories.get(category.id) }))
          .filter(category => category.name),
        images: { jpg: { large_image_url: attributes.posterImage?.large || attributes.posterImage?.original } },
        synopsis: attributes.synopsis
      };
    });

    renderGrid();

  } catch (error) {
    console.error('Gagal mengambil data dari Kitsu API:', error);
    mediaData = [];
    vibeGrid.innerHTML = '';
    errorMessage.textContent = error instanceof Error && error.message
      ? error.message
      : 'Terjadi kesalahan saat mengambil data. Silakan coba lagi.';
    errorState.classList.remove('hidden');
  } finally {
    loadingState.classList.add('hidden');
  }
};

// =========================================================================
// 4. Logika Penyaringan Data (Aura Discovery & Unified Search)
// =========================================================================
const getFilteredMedia = () => {
  return mediaData.filter((item) => {
    // Filter Real-time Title Search
    const title = item.title ? item.title.toLowerCase() : '';
    const matchesSearch = title.includes(searchKeyword.toLowerCase());

    // Extract daftar genre
    const genres = item.genres ? item.genres.map(g => g.name.toLowerCase()) : [];

    // Filter Aura-Based Discovery
    let matchesAura = true;

    if (currentAura === 'HIGH_ENERGY') {
      matchesAura = genres.some(g => ['action', 'sports', 'comedy', 'shounen', 'adventure'].includes(g));
    } else if (currentAura === 'MELANCHOLY') {
      matchesAura = genres.some(g => ['drama', 'slice of life', 'romance', 'supernatural', 'psychological'].includes(g));
    } else if (currentAura === 'BINGE_MODE') {
      const episodeCount = item.episodes || 0;
      const score = item.score || 0;
      matchesAura = episodeCount > 12 || score >= 8.0;
    }

    return matchesSearch && matchesAura;
  });
};

// =========================================================================
// 5. Render Data ke DOM Menggunakan Template Literal
// =========================================================================
const escapeHtml = (value) => String(value).replace(/[&<>"']/g, (character) => ({
  '&': '&amp;',
  '<': '&lt;',
  '>': '&gt;',
  '"': '&quot;',
  "'": '&#39;'
})[character]);

const renderGrid = () => {
  const filteredData = getFilteredMedia();

  if (filteredData.length === 0) {
    vibeGrid.innerHTML = `
      <div class="empty-state" style="grid-column: 1 / -1">
        <p class="state-title">Belum ada yang cocok dengan pencarianmu</p>
        <p class="state-copy">Coba kata kunci lain atau pilih aura "Semua".</p>
      </div>
    `;
    return;
  }

  const cardsHTML = filteredData.map((item) => {
    const title = escapeHtml(item.title || 'Judul Tidak Tersedia');
    const score = Number.isFinite(item.score) ? `★ ${item.score.toFixed(1)}` : 'N/A';
    const episodes = item.episodes ? `${item.episodes} Ep` : 'Ongoing/Unknown';
    const imageUrl = item.images?.jpg?.large_image_url || item.images?.jpg?.image_url;
    const type = escapeHtml(item.type || 'Media');
    
    const genreTags = item.genres ? item.genres.slice(0, 1).map(g => `
      <span class="genre-tag">
        ${escapeHtml(g.name)}
      </span>
    `).join('') : '';

    return `
      <a href="${escapeHtml(item.url)}" target="_blank" rel="noopener noreferrer" class="media-link" aria-label="Buka detail ${title} di Kitsu">
      <article class="media-card">
        <div class="media-poster">
          <img src="${escapeHtml(imageUrl || '')}" alt="Poster ${title}" loading="lazy">
          <div class="poster-shade"></div>
          <span class="score-badge">${score}</span>
          <div class="media-meta">
            <span class="media-type">${type}</span>
            <span>${escapeHtml(episodes)}</span>
          </div>
        </div>
        <div class="media-body">
          <h3 class="media-title" title="${title}">${title}</h3>
          <div class="genre-list">${genreTags}</div>
          <p class="media-description">${escapeHtml(item.synopsis || 'Deskripsi tidak tersedia untuk konten ini.')}</p>
        </div>
      </article>
      </a>
    `;
  }).join('');

  vibeGrid.innerHTML = cardsHTML;
};

// =========================================================================
// 6. Event Handling
// =========================================================================

// Event 1: Real-time Search Input
searchInput.addEventListener('input', (e) => {
  searchKeyword = e.target.value.trim();
  renderGrid();
});

// Event 2: Aura Buttons Click
auraButtons.forEach((button) => {
  button.addEventListener('click', () => {
    auraButtons.forEach(btn => {
    btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
    btn.setAttribute('aria-pressed', 'false');
    });

  button.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
  button.setAttribute('aria-pressed', 'true');

    currentAura = button.dataset.aura;
    renderGrid();
  });
});

// Event Retry Button
retryBtn.addEventListener('click', fetchVibeData);

// Event 3: Form Submit Validation
contactForm.addEventListener('submit', (e) => {
  e.preventDefault();
  alert('Terima kasih! Pesan Anda berhasil dikirim.');
  contactForm.reset();
});

// Load data saat pertama kali dijalankan
document.addEventListener('DOMContentLoaded', fetchVibeData);