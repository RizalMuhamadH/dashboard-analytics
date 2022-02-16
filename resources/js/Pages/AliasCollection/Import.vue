<template>
    <Head title="Page" />

    <BreezeAuthenticatedLayout>
        <h2
            class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
        >
            Import Data Websites
        </h2>

        <div
            class="
                md:w-2/5
                w-full
                my-4
                justify-self-center
                text-white
                bg-purple-600
                rounded-lg
                shadow-xs
                px-3
                py-4
            "
        >
            <input
                ref="excel-upload-input"
                class="hidden"
                type="file"
                accept=".xlsx, .xls, .csv"
                @change="handleClick"
            />
            <div
                class="
                    flex
                    h-40
                    border-2 border-white border-dashed
                    rounded-lg
                    items-center
                    justify-center
                    text-xl
                "
                @drop="handleDrop"
                @dragover="handleDragover"
                @dragenter="handleDragover"
            >
                Drop excel file here or
                <button :loading="loading" class="ml-2" @click="handleUpload">
                    Browse
                </button>
            </div>
        </div>

        <div class="my-6">
            <button
            v-if="excelData.header != null"
                :href="'#'"
                :class="'px-4 py-2 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple'"
                @click="uploadData"
            >
                Upload
            </button>
        </div>

        <div
            class="w-full overflow-hidden rounded-lg shadow-xs"
            v-if="excelData.header != null"
        >
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="
                                text-xs
                                font-semibold
                                tracking-wide
                                text-left text-gray-500
                                uppercase
                                border-b
                                dark:border-gray-700
                                bg-gray-50
                                dark:text-gray-400 dark:bg-gray-800
                            "
                        >
                            <th
                                class="px-4 py-3"
                                v-for="(item, index) of excelData.header"
                                :key="index"
                            >
                                {{ item }}
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="
                            bg-white
                            divide-y
                            dark:divide-gray-700 dark:bg-gray-800
                        "
                    >
                        <tr
                            class="text-gray-700 dark:text-gray-400"
                            v-for="(item, index) of excelData.results"
                            :key="index"
                        >
                            <td
                                class="px-4 py-3 text-sm"
                                v-for="(header, i) of excelData.header"
                                :key="i"
                            >
                                {{ item[header] }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import XLSX from "xlsx";
import Button from "@/Components/Button.vue";

export default {
    props: {
        beforeUpload: Function, // eslint-disable-line
        onSuccess: Function, // eslint-disable-line
    },
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Button,
        Link,
    },
    data() {
        return {
            loading: false,
            excelData: {
                header: null,
                results: null,
            },
            form: this.$inertia.form({
                import: []
            }),
        };
    },
    mounted() {
        if (this.$page.props.success.message != null) {
            console.log(this.$page.props.success.message);
            toastr.success(this.$page.props.success.message);
        }
    },
    methods: {
        uploadData(){
            this.form.import = this.excelData.results;

            this.form.post(
                    this.route("websites.store_import"),
                    {
                        onFinish: () => this.excelData = {
                            header: null,
                            results: null,
                        },
                        onSuccess: () => this.excelData = {
                            header: null,
                            results: null,
                        },
                        onError: (errors) => {
                            console.log(errors);
                        },
                    }
                );
        },
        generateData({ header, results }) {
            this.excelData.header = header;
            this.excelData.results = results;
            this.onSuccess && this.onSuccess(this.excelData);
        },
        handleDrop(e) {
            e.stopPropagation();
            e.preventDefault();
            if (this.loading) return;
            const files = e.dataTransfer.files;
            if (files.length !== 1) {
                this.$message.error("Only support uploading one file!");
                return;
            }
            const rawFile = files[0]; // only use files[0]

            if (!this.isExcel(rawFile)) {
                this.$message.error(
                    "Only supports upload .xlsx, .xls, .csv suffix files"
                );
                return false;
            }
            this.upload(rawFile);
            e.stopPropagation();
            e.preventDefault();
        },
        handleDragover(e) {
            e.stopPropagation();
            e.preventDefault();
            e.dataTransfer.dropEffect = "copy";
        },
        handleUpload() {
            this.$refs["excel-upload-input"].click();
        },
        handleClick(e) {
            console.log(e);
            const files = e.target.files;
            const rawFile = files[0]; // only use files[0]
            if (!rawFile) return;
            this.upload(rawFile);
        },
        upload(rawFile) {
            this.$refs["excel-upload-input"].value = null; // fix can't select the same excel

            if (!this.beforeUpload) {
                this.readerData(rawFile);
                return;
            }
            const before = this.beforeUpload(rawFile);
            if (before) {
                this.readerData(rawFile);
            }
        },
        readerData(rawFile) {
            this.loading = true;
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const data = e.target.result;
                    const workbook = XLSX.read(data, { type: "array" });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const header = this.getHeaderRow(worksheet);
                    const results = XLSX.utils.sheet_to_json(worksheet);
                    this.generateData({ header, results });
                    this.loading = false;
                    resolve();
                };
                reader.readAsArrayBuffer(rawFile);
            });
        },
        getHeaderRow(sheet) {
            const headers = [];
            const range = XLSX.utils.decode_range(sheet["!ref"]);
            let C;
            const R = range.s.r;
            /* start in the first row */
            for (C = range.s.c; C <= range.e.c; ++C) {
                /* walk every column in the range */
                const cell = sheet[XLSX.utils.encode_cell({ c: C, r: R })];
                /* find the cell in the first row */
                let hdr = "UNKNOWN " + C; // <-- replace with your desired default
                if (cell && cell.t) hdr = XLSX.utils.format_cell(cell);
                headers.push(hdr);
            }
            return headers;
        },
        isExcel(file) {
            return /\.(xlsx|xls|csv)$/.test(file.name);
        },
    },
};
</script>

<style scoped>
/* .excel-upload-input {
    display: none;
    z-index: -9999;
} */
.drop {
    border: 2px dashed #bbb;
    width: 600px;
    height: 160px;
    line-height: 160px;
    margin: 0 auto;
    font-size: 24px;
    border-radius: 5px;
    text-align: center;
    color: #bbb;
    position: relative;
}
</style>
