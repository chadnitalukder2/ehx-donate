<template>
    <div class="fc-gallery-wrap">
        <draggable class="fc-gallery-drag-area" :list="attachments" tag="ul" v-bind="dragOptions"
            draggable=".gallery-drag-item" @change="onMediaChange">

            <transition-group type="transition" name="flip-list">
                <li class="gallery-drag-item" v-for="(element, elementIndex) in attachments" :key="elementIndex">
                    <IconButton tag="button" size="tiny" @click.prevent="removeImageEvent(elementIndex)"
                        title="Delete media">
                        <el-icon >
                            <Delete />
                        </el-icon>
                    </IconButton>
                    <div class="icon-move" v-if="elementIndex !== 0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="8" height="10" viewBox="0 0 8 10" fill="none">
                            <rect x="0.333313" width="2.66667" height="2.66667" rx="0.666667" fill="white" />
                            <rect x="0.333313" y="3.6665" width="2.66667" height="2.66667" rx="0.666667" fill="white" />
                            <rect x="0.333313" y="7.3335" width="2.66667" height="2.66667" rx="0.666667" fill="white" />
                            <rect x="5" width="2.66667" height="2.66667" rx="0.666667" fill="white" />
                            <rect x="5.00018" y="3.6665" width="2.66667" height="2.66667" rx="0.666667" fill="white" />
                            <rect x="5.00018" y="7.3335" width="2.66667" height="2.66667" rx="0.666667" fill="white" />
                        </svg>
                    </div>
                    <el-tag class="featured-tag" type="primary" v-if="elementIndex === 0">Featured</el-tag>
                    <div class="gallery-drag-item-overlay" @click="showLightbox(elementIndex)"></div>
                    <img :src="element.url" :alt="element.title" />
                </li>
                <li :class="attachments.length < 1 ? 'is-full-media' : ''" :key="attachments.length">
                    <MediaButton :title="attachments.length < 1 ? 'Add Media' : ''"
                        :attachments="attachments" @onMediaSelected="onMediaSelected" :multiple='true'></MediaButton>
                </li>
            </transition-group>


        </draggable>
    </div>
    <VueEasyLightbox :visible="visible" :imgs="lightboxData" :index="imageIndex" @hide="() => {
        this.visible = false
    }"></VueEasyLightbox>
</template>

<script>
import { VueDraggableNext } from 'vue-draggable-next'
import MediaButton from "./buttons/MediaButton.vue";
import { ref } from "vue";
import IconButton from "./buttons/IconButton.vue";
import VueEasyLightbox from "vue-easy-lightbox";

export default {
    name: "Gallery",
    display: 'Transition',
    components: {
        draggable: VueDraggableNext,
        MediaButton,
        IconButton,
        VueEasyLightbox
    },
    props: {
        attachments: {
            type: Array,
            default: () => [],
        }
    },
    data() {
        return {
            visible: false,
            imageIndex: 0,
            lightboxData: [],
            images: ref([])
        };
    },
    emits: ['removeImage', 'mediaUploaded', 'onMediaChange'],
    methods: {
        showLightbox(idx) {
            this.imageIndex = idx;
            this.visible = true;
        },
        removeImageEvent(idx) {
            this.$emit('removeImage', idx);
        },
        onMediaSelected($selected) {
            this.images = $selected.map(image => ({
                id: image.id,
                title: image.title,
                url: image.url,
            }));

            this.$emit('mediaUploaded', this.images)
        },
        onMediaChange(event) {
            this.$emit('onMediaChange', this.attachments)
        },
    },
    computed: {
        dragOptions() {
            return {
                animation: 600
            }
        },
    },
    mounted() {
        if (this.attachments !== undefined) {
            this.attachments.map((val, index) => {
                this.lightboxData.push(val.url);
            })
        }
    },
    watch: {
        attachments: {
            handler: function (newval, oldval) {
                this.lightboxData = [];
                if (Array.isArray(newval) && newval.length > 0) {
                    newval.map((val, index) => {
                        this.lightboxData.push(val.url)
                    })
                }
                return newval
            },
            deep: true
        },
    }
};
</script>

<style lang="scss" scoped>
.fc-gallery-wrap {
    .fc-media-button {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px;
        border: 1px dashed #ccc;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 0 0 10px 0;

        &:hover {
            border-color: #999;
        }
    }

    .fc-gallery-drag-area {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        list-style: none;
        padding: 0;
        margin: 0;
        overflow: hidden;

        .gallery-drag-item, li {
            position: relative;
            width: calc(33% - 6px);
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow: 0 4px 12px var(--fraise-shadow);

            .icon-button {
                position: absolute;
                top: 10px;
                right: 10px;
                cursor: pointer;
                z-index: 10;
                background: #fff;
                border: 1px solid rgb(229, 228, 228);
                border-radius: 8px;
            }

            .icon-move {
                position: absolute;
                top: 10px;
                left: 10px;
                cursor: move;
                z-index: 10;
            }

            .gallery-drag-item-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
                transition: all 0.3s ease;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.2rem;
                z-index: 5;

                &:hover {
                    opacity: 1;
                }
            }

            img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            &:hover img {
                transform: scale(1.1);
            }
        }
        .gallery-drag-item:first-child {
            flex: 0 0 100%;
            height: 180px;
            position: relative !important;
            .featured-tag {
                position: absolute;
                top: 10px;
                left: 10px;
                z-index: 10;
            }
        }
        .fc-media-button {
            min-height: -webkit-fill-available;
            border-radius: 12px;
            margin-bottom: 0px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }
}
</style>