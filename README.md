Ethereum CLL Preprocessor
------

**Features**

- Allows you to enter c-like `//` comments

 ```cpp
 // This is a comment.
 val = tx.data[0]
 other = tx.data[1] // so is this.
 /* 
    and blocks
    like so 
 */
```
- Strips empty lines.

- Allows you to use c++ preprocessor-like defines

 ```cpp
 #define STATIC_ADDRESS 1001
 contract.storage[STATIC_ADDRESS] = 257
```

- Compiles your code using a branch of the Ethereum CLL Compiler: https://github.com/ethereum/compiler

- Allows you to compile just a snippet of code by deliminating the rest of the code with 3-or-more `=` signs (handy for debugging, or making reference code / notes)

 ```cpp
 // This gets processed.
 foo = 1 + 2
 contract.storage[100] = foo
 ====
 // This won't get processed.
 bar = 3 + 4
 contract.storage[101] = bar
```

----
**Extra Language Benefits**

Carries a PoC3 compatible branch of the [CLL Compiler](https://github.com/ethereum/compiler) as a submodule, which has some modifications.

One of which is it allows you to define functions.

 ```cpp
 // Define a function.
 def timesten(multiply):
     result = multiply * 10
     contract.storage[1024] = result
     return result

 // And call it.
 contract.storage[2048] = timesten(50)
```

...You can use multiple parameters, too. Such as `def multiparam(foo,bar,quux)` and call it like `multiparam(1,2,3)`

----
**Examples**

Right now, it just outputs the compiled code to stdout, and you copy and paste it. All you have to do is call the bash script using your `.cll` file location as the first parameter.

To just simply compile a .cll file, call the `compile.sh` script like so:

    ./compile.sh yourfile.cll

Try it with the included cll file.

    ./compile.sh example.cll

If you're looking to "inspect" the compiled EVM3-ASM, you can call it with the `-i` flag:

    ./compile.sh example.cll -i

And if you want to further trace something in that EVM3-ASM, you can add a regex which will point out the lines which match your regex, a la:

    ./compile.sh example.cll -i -t "JMP$" 

Which would match all `JMP` instructions, but not `JMPI` instructions.


----
**Setup**

You just have to init the [submodules](http://git-scm.com/book/en/Git-Tools-Submodules), and update, which will clone the other projects.

    [user@host dir]$ git submodule init
    [user@host dir]$ git submodule update

The submodules it includes are:

- [CLL Compiler](https://github.com/ethereum/compiler) on my PoC3 compatible branch.
- [Sublime Text 2 Syntax Higlighting Module](https://github.com/dougbtv/cll-syntaxhighlighter-st2)

---
**To Do**

- Still more