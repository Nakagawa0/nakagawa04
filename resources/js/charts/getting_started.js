// Chart.jsのライブラリを導入
import { Chart, ArcElement } from "chart.js/auto";

// ArcElement:円グラフ
Chart.register(ArcElement);

const ctx = document.getElementById("chart_getting_startsd");

if (ctx) {
    const protein = parseFloat(ctx.dataset.protein);
    const fat = parseFloat(ctx.dataset.fat);
    const carb = parseFloat(ctx.dataset.cabohydrate);

    new Chart(ctx, {
        type: "pie",
        data: {
            labels: ["Protein", "Fat", "Carbohydrate"],
            datasets: [
                {
                    data: [protein, fat, carb],
                    backgroundColor: ["#4caf50", "#ff9800", "#2196f3"],
                },
            ],
        },
    });
}
