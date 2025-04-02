import { router } from "@inertiajs/vue3" ;

export default function useConfirmDialog() {
    const confirmDelete = async (url, options = {}) => {
        const Swal = await import("sweetalert2").then(m => m.default) ;

        return Swal.fire({
            title : "Êtes-vous sûr ?",
            icon : "warning",
            showCancelButton : true,
            confirmButtonColor : "#3b82f6",
            cancelButtonColor : "#d33",
            confirmButtonText : "Oui, supprimer !",
            cancelButtonText : "Annuler",
            
        ...options
        }).then((result) => {
            if (result.isConfirmed) {
                router.delete(url, {
                    onSuccess : () => {
                        Swal.fire({
                            title: "Supprimé !",
                            text: "L'élément a été supprimé avec succès.",
                            icon: "success",
                            confirmButtonColor: "#3b82f6",
                        });
                    },
                    preserveScroll : true
                }) ;
            }
        }) ;
    } ;

    return { confirmDelete } ;
}
