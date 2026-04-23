// Book Haven - Frontend JS

(function () {
  function qs(sel, root) { return (root || document).querySelector(sel); }
  function qsa(sel, root) { return Array.from((root || document).querySelectorAll(sel)); }

  // ---------- Simple form validations ----------
  function showError(el, msg) {
    let box = el.parentElement.querySelector('.field-error');
    if (!box) {
      box = document.createElement('div');
      box.className = 'field-error';
      el.parentElement.appendChild(box);
    }
    box.textContent = msg;
  }

  function clearErrors(form) {
    qsa('.field-error', form).forEach(e => e.remove());
  }

  function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  qsa('form[data-validate="true"]').forEach(form => {
    form.addEventListener('submit', (e) => {
      clearErrors(form);
      let ok = true;

      qsa('[data-rule]', form).forEach(input => {
        const rule = input.getAttribute('data-rule');
        const val = (input.value || '').trim();

        if (rule.includes('required') && val === '') {
          ok = false; showError(input, 'This field is required.');
        }
        if (ok && rule.includes('email') && val !== '' && !validateEmail(val)) {
          ok = false; showError(input, 'Enter a valid email.');
        }
        if (ok && rule.includes('min4') && val.length < 4) {
          ok = false; showError(input, 'Minimum 4 characters.');
        }
      });

      if (!ok) e.preventDefault();
    });
  });

  // ---------- Customer: live search books ----------
  const searchBox = qs('#searchBox');
  const bookContainer = qs('#bookContainer');
  if (searchBox && bookContainer) {
    let timer = null;
    searchBox.addEventListener('keyup', function () {
      clearTimeout(timer);
      timer = setTimeout(() => {
        const query = searchBox.value;
        fetch('index.php?page=ajax_search_books&q=' + encodeURIComponent(query))
          .then(res => res.json())
          .then(data => {
            bookContainer.innerHTML = '';
            if (!data || data.length === 0) {
              bookContainer.innerHTML = '<p>No books found</p>';
              return;
            }
            data.forEach(book => {
              bookContainer.insertAdjacentHTML('beforeend', `
                <div class="card book-card">
                  <img src="assets/book.png" class="book-img" alt="Book">
                  <h3>${book.title}</h3>
                  <p>${book.author || ''}</p>
                  <p><strong>৳${book.price}</strong></p>
                  <form method="post" action="index.php?page=add_to_cart">
                    <input type="hidden" name="book_id" value="${book.id}">
                    <input type="hidden" name="title" value="${book.title}">
                    <input type="hidden" name="price" value="${book.price}">
                    <button type="submit">Add to Cart</button>
                  </form>
                </div>
              `);
            });
          });
      }, 150);
    });
  }

  // ---------- Employee: toggle book availability (AJAX) ----------
  qsa('[data-action="toggle-availability"]').forEach(btn => {
    btn.addEventListener('click', () => {
      const bookId = btn.getAttribute('data-book-id');
      const next = btn.getAttribute('data-next');
      const fd = new FormData();
      fd.append('book_id', bookId);
      fd.append('available', next);
      fetch('index.php?page=ajax_toggle_book', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(j => {
          if (!j.ok) {
            alert(j.message || 'Failed');
            return;
          }
          window.location.reload();
        });
    });
  });

  // ---------- Admin: delete employee (AJAX) ----------
  qsa('[data-action="delete-employee"]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-employee-id');
      if (!confirm('Delete this employee?')) return;
      const fd = new FormData();
      fd.append('employee_id', id);
      fetch('index.php?page=ajax_delete_employee', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(j => {
          if (!j.ok) {
            alert(j.message || 'Failed');
            return;
          }
          window.location.reload();
        });
    });
  });
})();
