// Published articles chart of the dashboard, inspired by the Sylius e-commerce statistics chart
(() => {
  const selector = '#article-statistics-chart';
  let chart = null;
  let renderedElement = null;
  let renderedSignature = null;

  const signatureOf = element => [
    element.dataset.intervals,
    element.dataset.articlesCount,
    document.documentElement.getAttribute('data-bs-theme'),
  ].join('|');

  const renderChart = () => {
    const element = document.querySelector(selector);

    if (!element || typeof ApexCharts === 'undefined') {
      return;
    }

    // Live Component re-renders morph the element and drop the chart, so render again when needed
    const signature = signatureOf(element);
    if (chart && element === renderedElement && signature === renderedSignature && element.querySelector('.apexcharts-canvas')) {
      return;
    }

    if (chart) {
      chart.destroy();
      chart = null;
    }

    const styles = getComputedStyle(document.documentElement);
    const labelColor = styles.getPropertyValue('--tblr-body-color').trim();
    const primaryColor = styles.getPropertyValue('--tblr-primary').trim();

    chart = new ApexCharts(element, {
      theme: {
        mode: document.documentElement.getAttribute('data-bs-theme') || 'light',
      },
      colors: [primaryColor],
      series: [{
        name: element.dataset.label,
        data: JSON.parse(element.dataset.articlesCount),
      }],
      chart: {
        background: 'transparent',
        toolbar: { show: false },
        height: 350,
        type: 'bar',
        events: {
          // Opens the published articles of the clicked day or month
          dataPointSelection: (event, chartContext, config) => {
            const range = JSON.parse(element.dataset.ranges)[config.dataPointIndex];

            if (!range) {
              return;
            }

            const params = new URLSearchParams({
              'criteria[status][status]': 'published',
              'criteria[publishStartDate][from][date]': range.from,
              'criteria[publishStartDate][to][date]': range.to,
            });

            window.location.href = `${element.dataset.redirect}?${params.toString()}`;
          },
        },
      },
      states: {
        active: { filter: { type: 'none' } },
      },
      plotOptions: {
        bar: {
          borderRadius: 4,
          dataLabels: { position: 'top' },
        },
      },
      dataLabels: {
        enabled: true,
        offsetY: -20,
        style: { colors: [labelColor] },
        formatter: val => (val > 0 ? val : ''),
      },
      xaxis: {
        categories: JSON.parse(element.dataset.intervals),
        labels: {
          style: { colors: labelColor },
          rotate: -45,
          // A month split into days has too many labels to be displayed horizontally
          rotateAlways: JSON.parse(element.dataset.intervals).length > 16,
        },
        axisBorder: { show: false },
        axisTicks: { show: false },
      },
      yaxis: {
        min: 0,
        // Article counts are integers, keep one tick per unit for small values to avoid duplicated labels
        tickAmount: Math.max(1, Math.min(5, Math.max(...JSON.parse(element.dataset.articlesCount)))),
        labels: {
          style: { colors: labelColor },
          formatter: val => Math.round(val),
        },
      },
    });
    chart.render();

    renderedElement = element;
    renderedSignature = signature;
  };

  let scheduled = false;
  const scheduleRender = () => {
    if (scheduled) {
      return;
    }

    scheduled = true;
    window.requestAnimationFrame(() => {
      scheduled = false;
      renderChart();
    });
  };

  document.addEventListener('DOMContentLoaded', () => {
    const element = document.querySelector(selector);

    if (!element) {
      return;
    }

    renderChart();

    new MutationObserver(scheduleRender).observe(element.closest('[data-controller~="live"]') || element.parentElement, {
      attributes: true,
      childList: true,
      subtree: true,
      attributeFilter: ['data-intervals', 'data-articles-count'],
    });

    new MutationObserver(scheduleRender).observe(document.documentElement, {
      attributes: true,
      attributeFilter: ['data-bs-theme'],
    });
  });
})();
