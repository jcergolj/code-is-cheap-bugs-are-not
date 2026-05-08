import * as Turbo from "@hotwired/turbo";
import Prism from "prismjs";
import "prismjs/plugins/line-highlight";

const NEXT_KEYS = ["ArrowRight", " ", "Spacebar"];
const PREVIOUS_KEYS = ["ArrowLeft", "Backspace"];

function isEditableTarget(target) {
    if (!target) return false;
    const tag = target.tagName;
    return tag === "INPUT" || tag === "TEXTAREA" || tag === "SELECT" || target.isContentEditable;
}

document.addEventListener("keydown", (event) => {
    if (event.ctrlKey || event.metaKey || event.altKey) return;
    if (isEditableTarget(event.target)) return;

    if (NEXT_KEYS.includes(event.key)) {
        const next = document.getElementById("next-button");
        if (next) {
            event.preventDefault();
            next.click();
        }
        return;
    }

    if (PREVIOUS_KEYS.includes(event.key)) {
        const previous = document.getElementById("previous-button");
        if (previous) {
            event.preventDefault();
            previous.click();
        }
    }
});

document.addEventListener("turbo:load", () => {
    Turbo.setProgressBarDelay(2);
    Prism.highlightAll();
});