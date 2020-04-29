<template>
  <div class="c-calendar-cont">
    <formModal
      :startDate="inputFormDate"
      @sendEvent="addEvent"
      :newEventId="newEventId"
      @updateId="incrementId"
    />
    <fullcalendar
      defaultView="dayGridMonth"
      :plugins="calendarPlugins"
      @eventRender="eventRender"
      :events="events"
      @dateClick="dateClick"
      @dayRender="dayRender"
      :displayEventTime="false"
    />
  </div>
</template>

<script>
import formModal from "./components/form_modal.vue";
import fullcalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interaction from "@fullcalendar/interaction";
import tippy from "tippy.js";
import Drop from "tether-drop";

export default {
  data: function() {
    return {
      events: [],
      calendarPlugins: [dayGridPlugin, interaction],
      inputFormDate: "",
      newEventId: "",
      updateTrigger: ""
    };
  },
  components: {
    fullcalendar,
    formModal
  },
  beforeCreate: function() {
    axios
      .get("/retrieve")
      .then(response => {
        this.events = response.data;
        // Set the unique id for new events;
        if (this.events[0] === undefined) {
          this.newEventId = 1;
        } else {
          this.newEventId = this.events.slice(-1)[0].id + 1;
        }
      })
      .catch(function(error) {
        console.log(error);
      });
  },
  mounted: function() {
    tippy("[data-tippy-content]");
  },
  updated: function() {
    tippy("[data-tippy-content]");
  },
  methods: {
    addEvent(event) {
      this.events = [...this.events, event];
    },
    dayRender(info) {
      info.el.classList.add("c-hover-pointer");
      info.el.classList.add("c-bg-element");
      console.log(info.el);
      info.el.onmouseover = function() {mouseOver()};
      function mouseOver() {
      }
    },
    dateClick: function(info) {
      this.$bvModal.show("modal-1");
      this.inputFormDate = info.dateStr;
    },
    eventRender(info) {
      // Poper content;
      let form = document.createElement("form");
      let input = document.createElement("input");
      let desc = document.createElement("textarea");
      let submit = document.createElement("input");
      let close = document.createElement("a");
      input.setAttribute("value", info.event.title);
      desc.value = info.event.extendedProps.description;
      input.setAttribute("class", "pop-input");
      submit.setAttribute("type", "button");
      submit.setAttribute("value", "Update");
      submit.setAttribute("class", "pop-submit");
      close.appendChild(document.createTextNode("x"));
      form.setAttribute("class", "updateForm");
      form.appendChild(close);
      form.appendChild(input);
      form.appendChild(desc);
      form.appendChild(submit);
      info.el.appendChild(form);
      info.el.classList.add("hover-pointer");
      //   Update
      submit.addEventListener("click", () => {
        if (input.value === "") {
          return;
        }
        let eventObj = this.events.find(obj => obj.id == info.event.id); //get the object in concern;
        let index = this.events.indexOf(eventObj);
        this.events[index].title = input.value;
        this.events[index].description = desc.value;
        let event = this.events[index];
        this.updateDb(event);
        drop.close();
      });
      //   Close update
      close.addEventListener("click", () => {
        drop.close();
      });
      //   Drop popover
      let drop = new Drop({
        target: info.el,
        content: form,
        classes: "c-poper drop-theme-basic",
        position: "bottom left",
        openOn: "click"
      });
      // Tooltip
      info.el.setAttribute(
        "data-tippy-content",
        info.event.extendedProps.description
      );
      // Event delete button
      let delBtn = document.createElement("a");
      delBtn.setAttribute("href", "#");
      delBtn.appendChild(document.createTextNode("x"));
      delBtn.setAttribute("class", "event-del");
      info.el.appendChild(delBtn);
      delBtn.addEventListener("click", () => {
        let eventObj = this.events.find(obj => obj.id == info.event.id);
        let index = this.events.indexOf(eventObj);
        this.events.splice(index, 1);
        axios
          .post("/remove", eventObj)
          .then(response => {
            console.log("deleted");
          })
          .catch(function(error) {
            console.log(error);
          });
        // Hide popover;
        form.parentNode.style.display = "none";
      });
      this.$forceUpdate(); // Basically for the tooltip to work on certain actions;
    },
    updateDb(event) {
      axios
        .post("/update", event)
        .then(response => {
          console.log("update");
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    incrementId() {
      this.newEventId++;
    }
  }
};
</script>

<style>
@import "~@fullcalendar/core/main.css";
@import "~@fullcalendar/daygrid/main.css";
@import "~tippy.js/dist/tippy.css";
@import "~tether-drop/dist/css/drop-theme-basic.css";
</style>

<style lang='scss' scoped>
.c-calendar-cont {
    max-width: 70vw;
}
</style>
