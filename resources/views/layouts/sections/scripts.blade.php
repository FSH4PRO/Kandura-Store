<!-- BEGIN: Vendor JS-->

@vite(['resources/assets/vendor/libs/jquery/jquery.js', 'resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js'])

@vite(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js', 'resources/assets/vendor/js/menu.js'])

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
@stack('page-script')
<!-- END: Page JS-->

<!-- app JS -->
@vite(['resources/js/app.js'])
<!-- END: app JS-->

<!-- Navbar Search Script -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('navbar-search-input');
    const searchForm = document.getElementById('navbar-search-form');
    const suggestionsDropdown = document.getElementById('search-suggestions');
    const suggestionsList = document.getElementById('search-suggestions-list');
    let searchTimeout;

    if (!searchInput || !suggestionsDropdown) return;

    // Handle input
    searchInput.addEventListener('input', function() {
      const query = this.value.trim();

      clearTimeout(searchTimeout);

      if (query.length < 2) {
        suggestionsDropdown.classList.add('d-none');
        return;
      }

      // Debounce search
      searchTimeout = setTimeout(() => {
        fetchSuggestions(query);
      }, 300);
    });

    // Handle form submit
    if (searchForm) {
      searchForm.addEventListener('submit', function(e) {
        const query = searchInput.value.trim();
        if (query.length < 2) {
          e.preventDefault();
          return false;
        }
      });
    }

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
      if (!searchInput.contains(e.target) && !suggestionsDropdown.contains(e.target)) {
        suggestionsDropdown.classList.add('d-none');
      }
    });

    // Show suggestions on focus if there's a query
    searchInput.addEventListener('focus', function() {
      if (this.value.trim().length >= 2) {
        fetchSuggestions(this.value.trim());
      }
    });

    function fetchSuggestions(query) {
      fetch(`{{ route('admin.search.suggestions') }}?q=${encodeURIComponent(query)}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          }
        })
        .then(response => response.json())
        .then(data => {
          displaySuggestions(data);
        })
        .catch(error => {
          console.error('Search error:', error);
        });
    }

    function displaySuggestions(suggestions) {
      if (suggestions.length === 0) {
        suggestionsList.innerHTML =
          '<div class="px-3 py-2 text-muted text-center">{{ __('navbar.no_suggestions') }}</div>';
        suggestionsDropdown.classList.remove('d-none');
        return;
      }

      let html = '';
      suggestions.forEach(item => {
        const icon = {
          'order': 'bx-cart',
          'customer': 'bx-user',
          'wallet': 'bx-wallet',
          'design': 'bx-image',
        } [item.type] || 'bx-file';

        html += `
          <a href="${item.url}" class="dropdown-item d-flex align-items-center">
            <i class="bx ${icon} me-2"></i>
            <div class="flex-grow-1">
              <div class="fw-medium">${escapeHtml(item.title)}</div>
              <small class="text-muted">${escapeHtml(item.subtitle || '')}</small>
            </div>
          </a>
        `;
      });

      suggestionsList.innerHTML = html;
      suggestionsDropdown.classList.remove('d-none');
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }
  });
</script>
