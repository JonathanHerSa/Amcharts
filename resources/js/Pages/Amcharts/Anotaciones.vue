<script setup>
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import * as Am5 from "@amcharts/amcharts5";
import * as Am5percent from "@amcharts/amcharts5/percent";
import Am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import * as Am5plugins_exporting from "@amcharts/amcharts5/plugins/exporting";

const form = useForm({
    ValorA: "",
});

onMounted(() => {
    let root = Am5.Root.new("chartdiv");

    root.setThemes([Am5themes_Animated.new(root)]);

    let chart = root.container.children.push(
        Am5percent.PieChart.new(root, {
            endAngle: 270,
        })
    );

    let series = chart.series.push(
        Am5percent.PieSeries.new(root, {
            valueField: "value",
            categoryField: "category",
            endAngle: 270,
        })
    );

    series.states.create("hidden", {
        endAngle: -90,
    });

    series.labels.template.set("forceHidden", true);
    series.ticks.template.set("forceHidden", true);

    series.data.setAll([
        {
            category: "Lithuania",
            value: 501.9,
        },
        {
            category: "Czechia",
            value: 301.9,
        },
        {
            category: "Ireland",
            value: 201.1,
        },
        {
            category: "Germany",
            value: 165.8,
        },
        {
            category: "Australia",
            value: 139.9,
        },
        {
            category: "Austria",
            value: 128.3,
        },
        {
            category: "UK",
            value: 99,
        },
    ]);

    series.appear(1000, 100);

    // Set up export and annotation
    let exporting = Am5plugins_exporting.Exporting.new(root, {
        menu: Am5plugins_exporting.ExportingMenu.new(root, {}),
    });

    let annotationData = {
        height: 700,
        width: 900,
        markers: [
            {
                bgColor: "#EF4444",
                tipPosition: { x: 276.00001525878906, y: 92.33334350585938 },
                color: "#FFFFFF",
                fontFamily: "Helvetica, Arial, sans-serif",
                padding: 5,
                text: "Needs improvement",
                left: 96.66667175292969,
                top: 178,
                width: 230,
                height: 48.333343505859375,
                rotationAngle: 0,
                visualTransformMatrix: { a: 1, b: 0, c: 0, d: 1, e: 0, f: 0 },
                containerTransformMatrix: {
                    a: 1,
                    b: 0,
                    c: 0,
                    d: 1,
                    e: 0,
                    f: 0,
                },
                typeName: "CalloutMarker",
                state: "select",
            },
            {
                arrowType: "end",
                strokeColor: "#7C3AED",
                strokeWidth: 3,
                strokeDasharray: "",
                x1: 733.3333740234375,
                y1: 125.33334350585938,
                x2: 539.3333740234375,
                y2: 225.6666717529297,
                typeName: "ArrowMarker",
                state: "select",
            },
            {
                arrowType: "end",
                strokeColor: "#7C3AED",
                strokeWidth: 3,
                strokeDasharray: "",
                x1: 736.6666870117188,
                y1: 153.33334350585938,
                x2: 518.6666870117188,
                y2: 343.0000915527344,
                typeName: "ArrowMarker",
                state: "select",
            },
            {
                color: "#EF4444",
                fontFamily: "Helvetica, Arial, sans-serif",
                padding: 5,
                text: "OK",
                left: 706.0000610351562,
                top: 125.00001525878906,
                width: 100,
                height: 30,
                rotationAngle: 0,
                visualTransformMatrix: { a: 1, b: 0, c: 0, d: 1, e: 0, f: 0 },
                containerTransformMatrix: {
                    a: 1,
                    b: 0,
                    c: 0,
                    d: 1,
                    e: 0,
                    f: 0,
                },
                typeName: "TextMarker",
                state: "select",
            },
        ],
    };

    let annotator = Am5plugins_exporting.Annotator.new(root, {});
    annotator.set("markerState", annotationData);

    let menuitems = exporting.get("menu").get("items");

    menuitems.push({
        type: "separator",
    });

    menuitems.push({
        type: "custom",
        label: "Annotate",
        callback: function () {
            this.close();
            annotator.toggle();
        },
    });
    const cambios = document.getElementById("save");
    cambios.addEventListener("click", (e) => {
        form.ValorA = annotator.get("markerState");
        submit();
    });
});
const submit = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("am5.notes"));
};
</script>
<template>
    <div class="w-full h-full" id="chartdiv"></div>
    <button class="bg-cyan-300 rounded-3xl w-fit px-3 py-1" id="save">
        Guardar Cambios
    </button>
</template>
