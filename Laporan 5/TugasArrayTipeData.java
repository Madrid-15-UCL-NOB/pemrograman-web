public class TugasArrayTipeData {
    public static void main(String[] args) {
        
        int[] angka = {10, 20, 30, 40, 50};
        System.out.println("Array Integer:");
        for (int i = 0; i < angka.length; i++) {
            System.out.println("angka[" + i + "] = " + angka[i]);
        }

       
        double[] nilai = {3.14, 2.5, 7.8};
        System.out.println("\nArray Double:");
        for (int i = 0; i < nilai.length; i++) {
            System.out.println("nilai[" + i + "] = " + nilai[i]);
        }

       
        char[] huruf = {'A', 'B', 'C', 'D'};
        System.out.println("\nArray Char:");
        for (int i = 0; i < huruf.length; i++) {
            System.out.println("huruf[" + i + "] = " + huruf[i]);
        }

        // Array tipe data boolean
        boolean[] status = {true, false, true};
        System.out.println("\nArray Boolean:");
        for (int i = 0; i < status.length; i++) {
            System.out.println("status[" + i + "] = " + status[i]);
        }

        // Array tipe data String (objek)
        String[] nama = {"Andi", "Budi", "Citra"};
        System.out.println("\nArray String:");
        for (int i = 0; i < nama.length; i++) {
            System.out.println("nama[" + i + "] = " + nama[i]);
        }
    }
}
