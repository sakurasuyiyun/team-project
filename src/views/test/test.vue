<script setup lang="ts">
import {get, post} from '@/utils/normalRequest'

let productName;
let productPrice;
let shopId;
let productCategory;

const file = async (e: Event) => {
  const formData = new FormData()
  formData.append('file', e.target.files[0])
  const res = await post('/api/uploadFile', formData, "multipart/form-data")
  if (res?.errno === 0) {
    console.log("上传图片成功")
    const addProducts = await get('/api/addProducts', {
      productName,
      productPrice,
      shopId,
      productCategory
    })
    if (addProducts?.errno === 0) {
      console.log("添加商品成功")
    }
  }
}
</script>

<template>
  <div>
    <input type="file" @change="file"/>
    <button>Upload</button>
  </div>
</template>

<style scoped>

</style>