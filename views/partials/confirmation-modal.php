<div id="confirm-modal" class="hidden">
    <div class="content">
        <h3>Confirmation</h3>
        <p><?= $confirmMessage ?? "Are you sure you want to take this action?" ?></p>
        <div class="confirm-btns">
            <button id="confirm-yes">Yes</button>
            <button id="confirm-no">No</button>
        </div>
    </div>
</div>

<style>
    #confirm-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(var(--text-colour-dark-rgb), 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    #confirm-modal.hidden{
        display: none;
    }
    
    #confirm-modal .content {
        background: var(--off-bg-colour);
        padding: 1rem;
        border: 1px solid var(--primary-colour);
        border-radius: 1rem;
        z-index: 1000;
    }

    #confirm-modal h3 {
        color: var(--primary-colour);
        margin-top: 0;
        margin-bottom: 0.2rem;
    }

    #confirm-modal button {
        display: inline-block;
        border: none;
        font-size: 1.2rem;
        padding: 0.4rem 0.8rem;
    }

    #confirm-modal button:hover{
        filter: brightness(80%);
    }

    #confirm-modal #confirm-yes{
        background-color: var(--primary-colour);
        color: var(--text-colour-light);
    }

    #confirm-modal #confirm-no{
        background-color: var(--dark-bg-colour);
    }
</style>