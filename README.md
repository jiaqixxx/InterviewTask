Author: Jiaqi

Though this task is just asked to import CSV file and parse its data to correct format,
in my opinion, it is better consider about the scalability of the project. 

1. Define 'AbstractFileImportSvc' class to handle different types of file import.
So far only have 'CsvImportSvc', in the future if need to handle Json or other types file, just define new service class to extend that abstract service and implememnt their own 'handleFileData' logic.

2. Probably will have other types transaction (differnet data structure in CSV file),
just implements the 'TransactionInterface' and write their own logic into functions 'setAttributes' and 'parseAttributes'.

3. 'TransactionCollection' implments 'IteratorAggregate', if later need to iterate the objects, it's faster and consume less memory than loop the normal array. Also, type hitting interface as argument for the 'add' function to make the 'TransactionCollection' class more flexible to add different types of objects(implements same interface).

4. Using PHPunit to test 'verifyKey' function.