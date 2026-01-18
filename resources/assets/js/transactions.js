/**
 * Transactions Page
 */

'use strict';

document.addEventListener('DOMContentLoaded', function (e) {
  // Initialize DataTable
  const transactionsTable = document.querySelector('.datatables-transactions');
  if (transactionsTable) {
    const dt = new DataTable(transactionsTable, {
      ajax: {
        url: window.location.href,
        type: 'GET',
        data: function (d) {
          // Add filter parameters
          d.type = document.getElementById('typeFilter')?.value || '';
          d.date_from = document.getElementById('dateFromFilter')?.value || '';
          d.date_to = document.getElementById('dateToFilter')?.value || '';
          d.search = document.getElementById('searchInput')?.value || '';
        }
      },
      columns: [
        { data: 'id', orderable: true },
        {
          data: 'customer',
          orderable: false,
          render: function (data, type, row) {
            return `
              <div class="d-flex align-items-center">
                <img src="${data.avatar || '/assets/img/avatars/1.png'}" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                <div>
                  <div class="fw-medium">${data.name}</div>
                  <small class="text-muted">${data.email}</small>
                </div>
              </div>
            `;
          }
        },
        {
          data: 'type',
          orderable: true,
          render: function (data) {
            const badgeClass = data === 'credit' ? 'success' : 'danger';
            return `<span class="badge bg-label-${badgeClass}">${data}</span>`;
          }
        },
        {
          data: 'amount',
          orderable: true,
          render: function (data, type, row) {
            const sign = row.type === 'credit' ? '+' : '-';
            const colorClass = row.type === 'credit' ? 'text-success' : 'text-danger';
            return `<span class="fw-medium ${colorClass}">${sign}${parseFloat(data).toFixed(2)} SAR</span>`;
          }
        },
        {
          data: 'created_at',
          orderable: true,
          render: function (data) {
            return new Date(data).toLocaleDateString('en-GB', {
              day: '2-digit',
              month: '2-digit',
              year: 'numeric',
              hour: '2-digit',
              minute: '2-digit'
            });
          }
        },
        {
          data: 'actions',
          orderable: false,
          render: function (data, type, row) {
            return `
              <a href="/transactions/${row.id}" class="btn btn-sm btn-outline-primary">
                <i class="bx bx-show me-1"></i>View
              </a>
            `;
          }
        }
      ],
      order: [[0, 'desc']],
      pageLength: 15,
      responsive: true,
      language: {
        paginate: {
          previous: '<i class="bx bx-chevron-left"></i>',
          next: '<i class="bx bx-chevron-right"></i>'
        }
      }
    });

    // Handle filters form submission
    document.getElementById('filtersForm')?.addEventListener('submit', function (e) {
      e.preventDefault();
      dt.ajax.reload();
    });

    // Clear filters
    document.getElementById('clearFilters')?.addEventListener('click', function () {
      document.getElementById('typeFilter').value = '';
      document.getElementById('dateFromFilter').value = '';
      document.getElementById('dateToFilter').value = '';
      dt.ajax.reload();
    });
  }
});
