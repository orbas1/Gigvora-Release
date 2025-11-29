const dashboard = document.getElementById('admin-live-dashboard');

const http = async (url) => {
    if (!url) return null;
    if (window.axios) {
        const response = await window.axios.get(url);
        return response.data;
    }
    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
        },
        credentials: 'include',
    });
    if (!response.ok) throw new Error(`Request failed: ${response.status}`);
    return response.json();
};

const asArray = (payload) => {
    if (!payload) return [];
    if (Array.isArray(payload.data)) return payload.data;
    if (Array.isArray(payload)) return payload;
    return [];
};

const formatDate = (value) => {
    if (!value) return 'TBC';
    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? 'TBC' : date.toLocaleString();
};

const computeHours = (webinars) =>
    webinars.reduce((total, webinar) => {
        const start = webinar.starts_at ? new Date(webinar.starts_at) : null;
        const end = webinar.ends_at ? new Date(webinar.ends_at) : null;
        if (start && end && !Number.isNaN(start) && !Number.isNaN(end)) {
            total += Math.max(0, end.getTime() - start.getTime()) / 3_600_000;
        } else if (webinar.duration_minutes) {
            total += webinar.duration_minutes / 60;
        }
        return total;
    }, 0);

const renderMetrics = (metricsRoot, values) => {
    if (!metricsRoot) return;
    metricsRoot.querySelector('[data-metric="upcoming-webinars"]')?.textContent = values.upcomingWebinars ?? '--';
    metricsRoot.querySelector('[data-metric="networking-events"]')?.textContent = values.networkingEvents ?? '--';
    metricsRoot.querySelector('[data-metric="interviews-week"]')?.textContent = values.interviewsThisWeek ?? '--';
    metricsRoot.querySelector('[data-metric="hours-recorded"]')?.textContent = values.hoursRecorded ?? '--';
};

const renderEvents = (tableBody, events) => {
    if (!tableBody) return;
    tableBody.innerHTML = '';
    if (!events.length) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-muted">No upcoming items found.</td></tr>';
        return;
    }

    events.forEach((event) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${event.type}</td>
            <td>${event.title}</td>
            <td>${event.date}</td>
            <td><span class="badge ${event.statusClass}">${event.statusLabel}</span></td>
            <td><a class="btn btn-sm btn-outline-primary" href="${event.href ?? '#'}">Manage</a></td>
        `;
        tableBody.appendChild(row);
    });
};

const logIssue = (issuesRoot, message) => {
    if (!issuesRoot) return;
    const li = document.createElement('li');
    li.textContent = message;
    issuesRoot.appendChild(li);
};

if (dashboard) {
    const metrics = document.getElementById('admin-metrics');
    const issues = document.getElementById('issue-log');
    const eventsBody = document.getElementById('admin-events-body');

    const loadLiveData = async () => {
        try {
            const [webinarPayload, networkingPayload, interviewPayload] = await Promise.all([
                http(dashboard.dataset.webinarsEndpoint),
                http(dashboard.dataset.networkingEndpoint),
                http(dashboard.dataset.interviewsEndpoint),
            ]);

            const webinars = asArray(webinarPayload);
            const networking = asArray(networkingPayload);
            const interviews = asArray(interviewPayload);

            const now = new Date();
            const sevenDays = 1000 * 60 * 60 * 24 * 7;

            const upcomingWebinars = webinars.filter((item) => item.starts_at && new Date(item.starts_at) >= now);
            const upcomingNetworking = networking.filter((item) => item.starts_at && new Date(item.starts_at) >= now);
            const interviewsThisWeek = interviews.filter((item) => {
                if (!item.scheduled_at) return false;
                const start = new Date(item.scheduled_at);
                return start >= now && start.getTime() - now.getTime() <= sevenDays;
            });

            renderMetrics(metrics, {
                upcomingWebinars: upcomingWebinars.length,
                networkingEvents: upcomingNetworking.length,
                interviewsThisWeek: interviewsThisWeek.length,
                hoursRecorded: computeHours(webinars).toFixed(1),
            });

            const normalizedEvents = [
                ...upcomingWebinars.map((item) => ({
                    type: 'Webinar',
                    title: item.title,
                    date: formatDate(item.starts_at),
                    sortValue: item.starts_at ? new Date(item.starts_at).getTime() : Number.MAX_SAFE_INTEGER,
                    statusClass: item.is_live ? 'bg-danger' : 'bg-warning text-dark',
                    statusLabel: item.is_live ? 'Live' : 'Scheduled',
                    href: item.url,
                })),
                ...upcomingNetworking.map((item) => ({
                    type: 'Networking',
                    title: item.title,
                    date: formatDate(item.starts_at),
                    sortValue: item.starts_at ? new Date(item.starts_at).getTime() : Number.MAX_SAFE_INTEGER,
                    statusClass: item.status === 'live' ? 'bg-success' : 'bg-warning text-dark',
                    statusLabel: item.status ? item.status : 'Planned',
                    href: item.url,
                })),
                ...interviewsThisWeek.map((item) => ({
                    type: 'Interview',
                    title: item.title,
                    date: formatDate(item.scheduled_at),
                    sortValue: item.scheduled_at ? new Date(item.scheduled_at).getTime() : Number.MAX_SAFE_INTEGER,
                    statusClass: 'bg-info text-dark',
                    statusLabel: 'Scheduled',
                    href: item.url,
                })),
            ].sort((a, b) => a.sortValue - b.sortValue);

            renderEvents(eventsBody, normalizedEvents.slice(0, 12));
        } catch (error) {
            console.error('Failed to load live data', error);
            logIssue(issues, `Dashboard refresh failed at ${new Date().toLocaleTimeString()}`);
        }
    };

    loadLiveData();
    setInterval(loadLiveData, 60_000);
}
