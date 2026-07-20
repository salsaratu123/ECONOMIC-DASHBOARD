export function updateNews(news) {
    const target = document.getElementById('newsList');
    if (!target) {
        return;
    }

    const groups = [
        ['Latest Economy News', news?.economy ?? []],
        ['Latest Trade News', news?.trade ?? []],
        ['Latest Geopolitic News', news?.geopolitic ?? []],
    ];

    if (!groups.some(([, articles]) => articles.length)) {
        target.innerHTML = '<div class="empty-state">No news available.</div>';
        return;
    }

    target.innerHTML = groups.map(([title, articles]) => `
        <h6 class="mt-2">${title}</h6>
        ${articles.length ? articles.slice(0, 2).map(renderArticle).join('') : '<div class="empty-state">No articles in this category.</div>'}
    `).join('');
}

function renderArticle(article) {
    const image = article.image
        ? `<img src="${article.image}" alt="" class="news-image">`
        : '<div class="news-image news-placeholder"><i class="bi bi-newspaper"></i></div>';

    return `
        <div class="news-card">
            ${image}
            <div>
                <a href="${article.url ?? '#'}" target="_blank" rel="noopener">${article.title ?? 'Untitled'}</a>
                <p>${article.description ?? ''}</p>
                <small>${article.source ?? '-'} | ${formatDate(article.published_at)}</small>
            </div>
        </div>
    `;
}

function formatDate(value) {
    if (!value) {
        return '-';
    }

    return new Date(value).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}
