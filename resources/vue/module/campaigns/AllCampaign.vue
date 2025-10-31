<template>
    <div class="ehxd_wrapper">

        <AppModal :title="'Add New Category'" :width="700" :showFooter="false" ref="add_category_modal">
            <template #body>
                <!-- <AddCategory @updateDataAfterNewAdd="handleAddedCategory" /> -->
            </template>
        </AppModal>

        <AppTable :tableData="categories" v-loading="loading">
            <template #header>
                <div class="ehxd_title">
                    <h1 class="table-title">All Category</h1>
                    <p class="table-short-dsc">Manage and view all your category</p>
                </div>
                <el-button @click="openCategoryAddModal()" size="large" type="primary" icon="Plus" class="ltm_button">
                    Add New Category
                </el-button>
            </template>

            <template #filter>
                <el-input class="ehxd-search-input ehxd_input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>

            <template #columns>
                <el-table-column prop="id" label="ID" width="60" />
                <el-table-column prop="name" label="Name" width="auto" />
                <el-table-column prop="slug" label="Slug" width="auto" />
                <el-table-column prop="directories" label="Directory" width="auto">
                    <template #default="scope">
                        <span>
                            {{scope.row.directories.map(d => d.name).join(', ')}}
                        </span>
                    </template>
                </el-table-column>
                <el-table-column prop="added_date" label="Add Date" width="auto">
                    <template #default="{ row }">
                        {{ formatAddedDate(row.created_at) }}
                    </template>
                </el-table-column>
                <el-table-column label="Operations" width="120">
                    <template #default="{ row }">
                        <el-tooltip class="box-item" effect="dark" content="Edit" placement="top-start">
                            <el-button @click="openUpdateCategoryModal(row)" class="ehxd_box_icon" link size="small">
                                <Icon icon="ehxd-edit" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="box-item" effect="dark" content="Delete" placement="top-start">
                            <el-button @click="openDeleteCategoryModal(row)" class="ehxd_box_icon" link size="small">
                                <Icon icon="ehxd-delete" />
                            </el-button>
                        </el-tooltip>
                    </template>
                </el-table-column>
            </template>

            <template #footer>
                <div class="ehxd_footer_page">
                    <p>Page {{ currentPage }} of {{ last_page }}</p>
                </div>

                <el-pagination v-model:current-page="currentPage" v-model:page-size="pageSize"
                    :page-sizes="[10, 20, 30, 40]" large :disabled="total_category <= pageSize" background
                    layout="total, sizes, prev, pager, next" :total="+total_category" />
            </template>

        </AppTable>

        <AppModal :title="'Update Category'" :width="700" :showFooter="false" ref="update_category_modal">
            <template #body>
                <div>
                    <!-- <AddCategory ref="addCategory" :categories_data="category"
                        @updateDataAfterNewAdd="handleUpdatedCategory" /> -->
                </div>
            </template>
            <template #footer>

            </template>
        </AppModal>


        <AppModal :title="'Delete Category'" :width="500" :showFooter="false" ref="delete_category_modal">
            <template #body>

                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this category</p>
                </div>
            </template>
            <template #footer>
                <div class="exd-modal-footer" style="text-align: center;">
                    <el-button @click="$refs.delete_category_modal.handleClose()" type="default"
                        size="medium">Cancel</el-button>
                    <el-button @click="deleteCategory" type="primary" size="medium">Delete</el-button>
                </div>
            </template>
        </AppModal>

    </div>
</template>

<script>
import axios from "axios";

import AppTable from "../../components/AppTable.vue";
import Icon from "../../components/Icons/AppIcon.vue";
import AppModal from "../../components/AppModal.vue";
// import AddCategory from "./AddCategory.vue";
export default {
    components: {
        AppTable,
        Icon,
        AppModal,
        // AddCategory
    },
    data() {
        return {
            search: '',
            categories: [],
            category: {},
            total_category: 0,
            loading: false,
            currentPage: 1,
            last_page: 1,
            pageSize: 10,
            active_id: null,
            add_category_modal: false,
            // nonce: window.EhxDirectoristData.nonce,
            // rest_api: window.EhxDirectoristData.rest_api,
        }
    },
    // watch: {
    //     currentPage() {
    //         this.getAllCategories();
    //     },
    //     pageSize() {
    //         this.currentPage = 1;
    //         this.getAllCategories();
    //     },
    //     search: {
    //         handler() {
    //             this.currentPage = 1;
    //             this.getAllCategories();
    //         },
    //         immediate: false
    //     },
    // },

    methods: {
        openCategoryAddModal() {
            if (this.$refs.add_category_modal) {
                this.$refs.add_category_modal.openModel();
            } else {
                console.log("Modal ref not found! Ensure AppModal is rendered.");
            }
        },

        formatAddedDate(date) {
            if (!date) return '';
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return new Date(date).toLocaleDateString('en-GB', options);
        },

        async getAllCategories() {
         //   this.loading = true;
            // try {
            //     const response = await axios.get(`${this.rest_api}/getAllCategories`, {
            //         params: {
            //             page: this.currentPage,
            //             limit: this.pageSize,
            //             search: this.search || '',
            //         },
            //         headers: {
            //             'X-WP-Nonce': this.nonce
            //         }
            //     });
            //     this.categories = response?.data?.categories_data;
            //     this.total_category = response?.data?.total || 0;
            //     this.last_page = response?.data?.last_page || 1;
            //     this.loading = false;
            // } catch (error) {
            //     this.loading = false;
            //     console.error('Error fetching couriers:',);
            // }
        },

        openDeleteCategoryModal(row) {
            this.active_id = row.id;
            this.$refs.delete_category_modal.openModel();
        },
        async deleteCategory() {
           // this.loading = true;
            const id = this.active_id;

            // try {
            //     const response = await axios.post(`${this.rest_api}/deleteCategory/${id}`, {}, {
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'X-WP-Nonce': this.nonce
            //         }
            //     });

            //     if (response.data.success === true) {
            //         this.$notify({
            //             title: 'Success',
            //             message: 'Category deleted successfully',
            //             type: 'success',
            //         });
            //         this.getAllCategories();
            //         this.$refs.delete_category_modal.handleClose();
            //     } else {
            //         this.$notify({
            //             title: 'Error',
            //             message: 'Failed to delete category',
            //             type: 'error',
            //         });
            //     }

            // } catch (error) {
            //     console.error('Error deleting category:', error);
            //     this.$notify({
            //         title: 'Error',
            //         message: 'An unexpected error occurred while deleting the category.',
            //         type: 'error',
            //     });
            // } finally {
            //     this.loading = false;
            // }
        },

        openUpdateCategoryModal(row) {
            this.category = row;
            this.$refs.update_category_modal.openModel();
        },

        handleUpdatedCategory(updated) {
            this.getAllCategories(); // or update the array locally
            this.$refs.update_category_modal.handleClose();
        },

        handleAddedCategory(newCategory) {
            this.getAllCategories();
            // this.$refs.add_category_modal.handleClose();
        }

    },

    mounted() {
        // console.log('window', window);
        this.getAllCategories();
    },

}
</script>

<style lang="scss" scoped></style>