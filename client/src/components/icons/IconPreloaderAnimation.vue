<template>
    <div class="kabobloader">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</template>

<style scoped>
/* Preloader animation */
.kabobloader {
  margin: auto;
  top:0;
  right:0;
  bottom:0;
  left:0;
  position:absolute;
  width: 70px;
  height: 70px;
}
.kabobloader > div {
  width: 18px;
  height: 18px;
  background-color: #2EBD59;
  border-radius: 100%;
  display: inline-block;
  -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
  animation: sk-bouncedelay 1.4s infinite ease-in-out both;
}
.kabobloader .bounce1 {
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}
.kabobloader .bounce2 {
  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}
@-webkit-keyframes sk-bouncedelay {
  0%, 80%, 100% { -webkit-transform: scale(0) }
  40% { -webkit-transform: scale(1.0) }
}
@keyframes sk-bouncedelay {
  0%, 80%, 100% { 
    -webkit-transform: scale(0);
    transform: scale(0);
  } 40% { 
    -webkit-transform: scale(1.0);
    transform: scale(1.0);
  }
}
</style>