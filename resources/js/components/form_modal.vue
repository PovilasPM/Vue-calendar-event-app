<template>
  <b-modal class="c-modal" id="modal-1" title="New event" hide-footer>
    <form class="mx-3">
      <div class="input-holder">
        <p class="c-modal-text w-100 h5 text-secondary">Event-date: {{this.startDate}}</p>
      </div>
      <div class="input-holder">
        <input class="w-100" type="text" placeholder="Event title" v-model="event.title" />
      </div>
      <div class="input-holder">
        <!-- Datepicker -->
        <date-picker class="c-date-picker w-100" placeholder="End date" v-model="event.end" />
      </div>
      <div class="input-holder">
        <textarea
          class="w-100"
          placeholder="Event description"
          rows="4"
          v-model="event.description"
        ></textarea>
      </div>
      <div class="input-holder">
        <!-- Colorpicker -->
        <color-picker @colorPicked="selectColor" :color="event.classNames" />
      </div>
      <div class="input-holder">
        <button class="d-block m-auto" type="button" @click="createEvent()">Schedule</button>
      </div>
    </form>
  </b-modal>
</template>


<script>
import ColorPicker from "./ColorPicker";
import DatePicker from "vuejs-datepicker";

export default {
  name: "EventForm",
  data() {
    return {
      event: {
        id: "",
        title: "",
        description: "no descrition",
        start: "",
        end: "",
        classNames: ""
      }
    };
  },
  props: ["startDate", "newEventId"],
  methods: {
    createEvent() {
      if (this.event.title === "") {
        return;
      }
      this.event.id = this.newEventId;
      this.event.start = this.startDate;
      this.$emit("sendEvent", this.event);
      this.$emit("updateId");
      this.updateDb();
      this.resetValues();
      this.$bvModal.hide("modal-1");
    },
    updateDb() {
      let event = this.event;
      axios
        .post("/create", event)
        .then(response => {
          console.log("created");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    resetValues() {
      this.event = {
        id: "",
        title: "",
        description: "no descrition",
        start: "",
        end: "",
        classNames: ""
      };
    },
    selectColor(color) {
      this.event = {
        ...this.event,
        classNames: color
      };
    }
  },
  components: {
    DatePicker,
    ColorPicker
  }
};
</script>

<style lang="scss" scoped>

form {
  display: flex;
  flex-direction: column;
}
.input-holder {
  margin: 10px 0;
  display: flex;
  justify-content: flex-start;
}
.vdp-datepicker {
  width: 100%;
}

.vdp-datepicker > div > input {
  width: 77%;
}

.input-holder > button {
  justify-self: center;
  padding: 12px 25px;
  border-radius: 5px;
  text-transform: uppercase;
  font-weight: 600;
  background: #007bff;
  color: white;
  border: 2px solid #007bff;
  font-size: 14px;
  letter-spacing: -0.1px;
  cursor: pointer;
  &:hover {
      background: white;
      color: #007bff;
      border: 2px solid #007bff;
  }
}

input,
textarea {
  padding: 12px 15px;
  border: 2px solid rgba(0, 0, 0, 0.2);
  border-radius: 0;
  width: 70%;
  opacity: 0.8;
  font-size: 15px;
  font-weight: normal;
}

input:focus,
textarea:focus,
button:focus {
  border: 2px solid orangered;
  outline: none;
  box-shadow: 0 2px 3px 1px rgba(0, 0, 0, 0.2);
}
</style>
